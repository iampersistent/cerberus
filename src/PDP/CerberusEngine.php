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
                $resultIndividualDecision = new StdMutableResult($requestIndividualDecision->getStatus()); // was StdMutableResponse
            } else {
                EvaluationContext $evaluationContext = $this->evaluationContextFactory->getEvaluationContext($requestIndividualDecision);
                if ($evaluationContext == null) {
                    $resultIndividualDecision = new StdMutableResult(
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
                Decision $decision = $resultIndividualDecision->getDecision();
                Status $status = $resultIndividualDecision->getStatus();
                if ($resultIndividualDecision->getAssociatedAdvice()->size() > 0) {
                    $decision = Decision::INDETERMINATE;
                    status = STATUS_ADVICE_NA;
                } else {
                    if ($resultIndividualDecision->getObligations()->size() > 0) {
                        $decision = Decision::INDETERMINATE;
                        status = STATUS_OBLIGATIONS_NA;
                    }
                }

                if ($resultCombined == null) {
                    $resultCombined = new StdMutableResult(decision, status);
                } else {
                    if ($resultCombined->getDecision() != $resultIndividualDecision->getDecision()) {
                        $resultCombined->setDecision(Decision::INDETERMINATE);
                        $resultCombined->setStatus(STATUS_COMBINE_FAILED);
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
                Collection < AttributeCategory> listRequestAttributesIncludeInResult = $evaluationContext
                    . getRequest() . getRequestAttributesIncludedInResult();
                    if (listRequestAttributesIncludeInResult != null
                        && listRequestAttributesIncludeInResult . size() > 0
                    ) {
                        StdMutableResult stdMutableResult = new StdMutableResult(result);
                        stdMutableResult . addAttributeCategories(listRequestAttributesIncludeInResult);
                        $result = new StdResult(stdMutableResult);
                    }
                }

            return $result;
        } catch (EvaluationException $e) {
            return new StdMutableResult(new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR, $e->getMessage()));
        }
    }
}