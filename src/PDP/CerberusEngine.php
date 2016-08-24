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
use Cerberus\PDP\Exception\EvaluationException;
use Cerberus\PDP\Policy\PolicyDef;

class CerberusEngine implements PdpEngine
{

    protected $defaultDecision = Decision::INDETERMINATE;

    /** @var IndividualDecisionRequestGenerator */
    protected $individualDecisionRequestGenerator;

    public function decide(Request $request): Response
    {
        /*
         * Validate the request
         */
        // call TraceEngine->trace()
        // if its not ok return status report

        /*
         * Split the original request up into individual decision requests
         */
        // StdIndividualDecisionRequestGenerator

        /*
         * Determine if we are combining multiple $results into a single $result
         */
        // $combineResults = $request.getCombinedDecision();: boolean
        // $resultsCombined = null;

        /*
         * Iterate over all of the individual decision requests and process them, combining them into the
         * final response
         */
        $requestsIndividualDecision = $this->individualDecisionRequestGenerator->getIndividualDecisionRequests();
        if ($requestsIndividualDecision == null || ! $requestsIndividualDecision->hasNext()) {

            return new Response(new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR,
                "No individual decision requests")); // was StdMutableResponse
        }

        while ($requestIndividualDecision = $requestsIndividualDecision->next()) {
            $requestIndividualDecision = $requestsIndividualDecision->next(); //Request
//            if (traceEngineThis.isTracing()) {
//                traceEngineThis.trace(new StdTraceEvent<Request>("Individual Request", $this,
//                                                                 $requestIndividualDecision));
//            }
            if ($requestIndividualDecision->getStatus() != null
                && ! $requestIndividualDecision->getStatus()->isOk()
            ) {
                $resultIndividualDecision = new MutableResult($requestIndividualDecision->getStatus()); // was StdMutableResponse
            } else {
                $evaluationContext = $this->evaluationContextFactory->getEvaluationContext($requestIndividualDecision); // EvaluationContext
                if ($evaluationContext == null) {
                    $resultIndividualDecision = new MutableResult(
                        new Status(
                            StatusCode::STATUS_CODE_PROCESSING_ERROR,
                            "Null EvaluationContext"));
                } else {
                    $resultIndividualDecision = $this->processRequest($evaluationContext);
                }
            }

//            assert $resultIndividualDecision != null;
//            if (traceEngineThis.isTracing()) {
//                traceEngineThis.trace(new StdTraceEvent<Result>("Individual Result", $this,
//                                                                $resultIndividualDecision));
//            }
            if ($combineResults) {
                $decision = $resultIndividualDecision->getDecision(); // Decision
                $status = $resultIndividualDecision->getStatus(); // Status
                if ($resultIndividualDecision->getAssociatedAdvice()->size() > 0) {
                    $decision = Decision::INDETERMINATE;
                    $status = new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "Advice not allowed in combined decision");
                } else {
                    if ($resultIndividualDecision->getObligations()->size() > 0) {
                        $decision = Decision::INDETERMINATE;
                        $status = new Status(
                            StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                            "Obligations not allowed in combined decision");
                    }
                }

                if ($resultCombined == null) {
                    $resultCombined = new MutableResult($decision, $status);
                } else {
                    if ($resultCombined->getDecision() != $resultIndividualDecision->getDecision()) {
                        $resultCombined->setDecision(Decision::INDETERMINATE);
                        $resultCombined->setStatus(new Status(
                            StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                            "Individual decisions do not match"));
                    }
                }
                $resultCombined->addPolicyIdentifiers($resultIndividualDecision->getPolicyIdentifiers());
                $resultCombined->addPolicySetIdentifiers($resultIndividualDecision->getPolicySetIdentifiers());
                $resultCombined->addAttributeCategories($resultIndividualDecision->getAttributes());
//                if (traceEngineThis.isTracing()) {
//                    traceEngineThis.trace(new StdTraceEvent<Result>("Combined $result", $this,
//                                                                    $resultCombined));
//                }
            } else {
                $response->add($resultIndividualDecision);
            }
        }

        if ($combineResults) {
            $response->add($resultCombined);
        }

        return $response;
    }


    protected function processRequest(EvaluationContext $evaluationContext): Result
    {
        try {
            $policyFinderResult = $evaluationContext->getRootPolicyDef();
            if ($policyFinderResult->getStatus() != null && ! $policyFinderResult->getStatus()->isOk()) {
                return new StdMutableResult($policyFinderResult->getStatus());
            }

            /** @var PolicyDef $policyDefRoot */
            $policyDefRoot = $policyFinderResult->getPolicyDef();
            if ($policyDefRoot == null) {
                switch ($this->defaultDecision) {
                    case Decision::DENY:
                    case Decision::NOT_APPLICABLE:
                    case Decision::PERMIT:
                        return new StdMutableResult($this->defaultDecision,
                            new Status(StatusCode::STATUS_CODE_OK,
                                "No applicable policy"));
                    case Decision::INDETERMINATE:
                    case Decision::INDETERMINATE_DENY:
                    case Decision::INDETERMINATE_DENY_PERMIT:
                    case Decision::INDETERMINATE_PERMIT:
                        return new StdMutableResult($this->defaultDecision,
                            new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR,
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
                        $mutableResult = new MutableResult(result); // StdMut...
                        $mutableResult->addAttributeCategories($listRequestAttributesIncludeInResult);
                        $result = new Result($mutableResult); // stdResult
                    }
                }

            return $result;
        } catch (EvaluationException $e) {
            return new MutableResult(new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), $e->getMessage()));
        }
    }
}