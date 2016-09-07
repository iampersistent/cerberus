<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\Core\Decision;
use Cerberus\Core\IndividualDecisionRequestGenerator;
use Cerberus\Core\Request;
use Cerberus\Core\Response;
use Cerberus\Core\Result;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Contract\PdpEngine;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationContextFactory;
use Cerberus\PDP\Exception\EvaluationException;
use Cerberus\PDP\Policy\PolicyDef;
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PIP\PipFinder;

class CerberusEngine implements PdpEngine
{
    protected $defaultDecision = Decision::INDETERMINATE;

    /** @var EvaluationContextFactory */
    protected $evaluationContextFactory;

    /** @var PipFinder */
    protected $pipFinder;

    /** @var PolicyFinder */
    protected $policyFinder;

    /** @var ScopeResolver */
    protected $scopeResolver;

    public function __construct(PolicyFinder $policyFinder, PipFinder $pipFinder)
    {
        $this->evaluationContextFactory = new EvaluationContextFactory($policyFinder, $pipFinder);
        $this->pipFinder = $pipFinder;
        $this->policyFinder = $policyFinder;
        $this->scopeResolver = new ScopeResolver();
    }

    public function decide(Request $request): Response
    {
        /*
         * Validate the request
         */
        // call TraceEngine->trace()

        $statusRequest = $request->getStatus();
        if ($statusRequest && ! $statusRequest->isOk()) {
            return new MutableResponse($statusRequest);
        }

        /*
         * Split the original request up into individual decision requests
         */
        $individualDecisionRequestGenerator = new IndividualDecisionRequestGenerator($this->scopeResolver, $request);

        /*
         * Determine if we are combining multiple $results into a single $result
         */
        $combineResults = $request->getCombinedDecision(); // boolean
        $resultsCombined = null; // MutableResult

        /*
         * Iterate over all of the individual decision requests and process them, combining them into the
         * final response
         */
        $response = new MutableResponse();

        $requestsIndividualDecision = $individualDecisionRequestGenerator->getIndividualDecisionRequests();
        if ($requestsIndividualDecision->isEmpty()) {
            return new MutableResponse(new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                "No individual decision requests"));
        }

        foreach ($requestsIndividualDecision as $individualDecision) {
//            if (traceEngineThis.isTracing()) {
//                traceEngineThis.trace(new StdTraceEvent<Request>("Individual Request", $this,
//                                                                 $requestIndividualDecision));
//            }
            $status = $individualDecision->getStatus();
            if ($status && ! $status->isOk()) {
                $individualDecisionResult = new MutableResult(); // was StdMutableResponse
            } else {
                $evaluationContext = $this->evaluationContextFactory->getEvaluationContext($individualDecision); // EvaluationContext
                if ($evaluationContext == null) {
                    $individualDecisionResult = new MutableResult(
                        new Status(
                            StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                            "Null EvaluationContext"));
                } else {
                    $individualDecisionResult = $this->processRequest($evaluationContext);
                }
            }

//            assert $resultIndividualDecision != null;
//            if (traceEngineThis.isTracing()) {
//                traceEngineThis.trace(new StdTraceEvent<Result>("Individual Result", $this,
//                                                                $resultIndividualDecision));
//            }
            if ($combineResults) {
                $decision = $individualDecisionResult->getDecision(); // Decision
                $status = $individualDecisionResult->getStatus(); // Status
                if ($individualDecisionResult->getAssociatedAdvice()->size() > 0) {
                    $decision = Decision::INDETERMINATE();
                    $status = new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                        "Advice not allowed in combined decision");
                } else {
                    if ($individualDecisionResult->getObligations()->size() > 0) {
                        $decision = Decision::INDETERMINATE();
                        $status = new Status(
                            StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                            "Obligations not allowed in combined decision");
                    }
                }

                /** @var MutableResult $resultsCombined */
                if ($resultsCombined == null) {
                    $resultsCombined = new MutableResult($decision, $status);
                } else {
                    if ($resultsCombined->getDecision() != $individualDecisionResult->getDecision()) {
                        $resultsCombined->setDecision(Decision::INDETERMINATE());
                        $resultsCombined->setStatus(new Status(
                            StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                            "Individual decisions do not match"));
                    }
                }
                $resultsCombined->addPolicyIdentifiers($individualDecisionResult->getPolicyIdentifiers());
                $resultsCombined->addPolicySetIdentifiers($individualDecisionResult->getPolicySetIdentifiers());
                $resultsCombined->addAttributeCategories($individualDecisionResult->getAttributes());
//                if (traceEngineThis.isTracing()) {
//                    traceEngineThis.trace(new StdTraceEvent<Result>("Combined $result", $this,
//                                                                    $resultCombined));
//                }
            } else {
                $response->add($individualDecisionResult);
            }
        }

        if ($combineResults) {
            $response->add($resultsCombined);
        }

        return $response;
    }


    protected function processRequest(EvaluationContext $evaluationContext): Result
    {
        try {
            $policyFinderResult = $evaluationContext->getRootPolicyDef();
            if ($policyFinderResult->getStatus() != null && ! $policyFinderResult->getStatus()->isOk()) {
                return new MutableResult($policyFinderResult->getStatus());
            }

            /** @var PolicyDef $policyDefRoot */
            $policyDefRoot = $policyFinderResult->getPolicyDef();
            if ($policyDefRoot == null) {
                switch ($this->defaultDecision) {
                    case Decision::DENY:
                    case Decision::NOT_APPLICABLE:
                    case Decision::PERMIT:
                        return new MutableResult($this->defaultDecision,
                            new Status(StatusCode::STATUS_CODE_OK(),
                                "No applicable policy"));
                    case Decision::INDETERMINATE:
                    case Decision::INDETERMINATE_DENY:
                    case Decision::INDETERMINATE_DENY_PERMIT:
                    case Decision::INDETERMINATE_PERMIT:
                        return new MutableResult($this->defaultDecision,
                            new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                                "No applicable policy"));
                }
            }
            /** @var Result $result */
            $result = $policyDefRoot->evaluate($evaluationContext);
            if ($result->getStatus()->isOk()) {
                $listRequestAttributesIncludeInResult = $evaluationContext->getRequest()->getRequestAttributesIncludedInResult(); //Collection < AttributeCategory>
                if ($listRequestAttributesIncludeInResult != null
                    && count($listRequestAttributesIncludeInResult) > 0
                ) {
                    $mutableResult = new MutableResult($result);
                    $mutableResult->addAttributeCategories($listRequestAttributesIncludeInResult);
                    $result = new Result($mutableResult);
                }
            }

            return $result;
        } catch (EvaluationException $e) {
            return new MutableResult(new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), $e->getMessage()));
        }
    }
}