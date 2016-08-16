<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\Core\Decision;
use Cerberus\PDP\Contract\PDPEngine;

class CerberusEngine implements PDPEngine
{
    protected $defaultDecision = Decision::INDETERMINATE;

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
         * Determine if we are combining multiple results into a single result
         */
        // $combineResults = $request.getCombinedDecision();: boolean
        // $resultsCombined = null;

        /*
         * Iterate over all of the individual decision requests and process them, combining them into the
         * final response
         */
        StdMutableResponse stdResponse = new StdMutableResponse();
        Iterator<Request> iterRequestsIndividualDecision = stdIndividualDecisionRequestGenerator
        .getIndividualDecisionRequests();
        if (iterRequestsIndividualDecision == null || !iterRequestsIndividualDecision.hasNext()) {
            return new StdMutableResponse(new StdStatus(StdStatusCode.STATUS_CODE_PROCESSING_ERROR,
                "No individual decision requests"));
        }

        while (iterRequestsIndividualDecision.hasNext()) {
            Request requestIndividualDecision = iterRequestsIndividualDecision.next();
            if (traceEngineThis.isTracing()) {
                traceEngineThis.trace(new StdTraceEvent<Request>("Individual Request", this,
                                                                 requestIndividualDecision));
            }
            Result resultIndividualDecision = null;
            if (requestIndividualDecision.getStatus() != null
                && !requestIndividualDecision.getStatus().isOk()) {
                resultIndividualDecision = new StdMutableResult(requestIndividualDecision.getStatus());
            } else {
                EvaluationContext evaluationContext = this.evaluationContextFactory
                    .getEvaluationContext(requestIndividualDecision);
                if (evaluationContext == null) {
                    resultIndividualDecision = RESULT_ECTX_NULL;
                } else {
                    resultIndividualDecision = this.processRequest(evaluationContext);
                }
            }

            assert resultIndividualDecision != null;
            if (traceEngineThis.isTracing()) {
                traceEngineThis.trace(new StdTraceEvent<Result>("Individual Result", this,
                                                                resultIndividualDecision));
            }
            if (bCombineResults) {
                Decision decision = resultIndividualDecision.getDecision();
                Status status = resultIndividualDecision.getStatus();
                if (resultIndividualDecision.getAssociatedAdvice().size() > 0) {
                    decision = Decision.INDETERMINATE;
                    status = STATUS_ADVICE_NA;
                } else if (resultIndividualDecision.getObligations().size() > 0) {
                    decision = Decision.INDETERMINATE;
                    status = STATUS_OBLIGATIONS_NA;
                }

                if (stdResultCombined == null) {
                    stdResultCombined = new StdMutableResult(decision, status);
                } else {
                    if (stdResultCombined.getDecision() != resultIndividualDecision.getDecision()) {
                        stdResultCombined.setDecision(Decision.INDETERMINATE);
                        stdResultCombined.setStatus(STATUS_COMBINE_FAILED);
                    }
                }
                stdResultCombined.addPolicyIdentifiers(resultIndividualDecision.getPolicyIdentifiers());
                stdResultCombined.addPolicySetIdentifiers(resultIndividualDecision.getPolicySetIdentifiers());
                stdResultCombined.addAttributeCategories(resultIndividualDecision.getAttributes());
                if (traceEngineThis.isTracing()) {
                    traceEngineThis.trace(new StdTraceEvent<Result>("Combined result", this,
                                                                    stdResultCombined));
                }
            } else {
                stdResponse.add(resultIndividualDecision);
            }
        }

        if (bCombineResults) {
            stdResponse.add(stdResultCombined);
        }
        return stdResponse;
    }


protected function processRequest(EvaluationContext evaluationContext): Result
{
try {
PolicyFinderResult<PolicyDef> policyFinderResult = evaluationContext.getRootPolicyDef();
if (policyFinderResult.getStatus() != null && !policyFinderResult.getStatus().isOk()) {
return new StdMutableResult(policyFinderResult.getStatus());
}
PolicyDef policyDefRoot = policyFinderResult.getPolicyDef();
            if (policyDefRoot == null) {
                switch ($this->defaultDecision) {
                    case Decision::DENY:
                    case Decision::NOTAPPLICABLE:
                    case Decision::PERMIT:
                        return new StdMutableResult(this.defaultDecision,
                            new StdStatus(StdStatusCode.STATUS_CODE_OK,
                                "No applicable policy"));
                    case Decision::INDETERMINATE:
                    case Decision::INDETERMINATE_DENY:
                    case Decision::INDETERMINATE_DENYPERMIT:
                    case Decision::INDETERMINATE_PERMIT:
                        return new StdMutableResult(this.defaultDecision,
                            new StdStatus(StdStatusCode.STATUS_CODE_PROCESSING_ERROR,
                                "No applicable policy"));
                }
            }
            Result result = policyDefRoot.evaluate(evaluationContext);
            if (result.getStatus().isOk()) {
                Collection<AttributeCategory> listRequestAttributesIncludeInResult = evaluationContext
                    .getRequest().getRequestAttributesIncludedInResult();
                if (listRequestAttributesIncludeInResult != null
                    && listRequestAttributesIncludeInResult.size() > 0) {
                    StdMutableResult stdMutableResult = new StdMutableResult(result);
                    stdMutableResult.addAttributeCategories(listRequestAttributesIncludeInResult);
                    result = new StdResult(stdMutableResult);
                }
            }
            return result;
        } catch (EvaluationException ex) {
    return new StdMutableResult(new StdStatus(StdStatusCode.STATUS_CODE_PROCESSING_ERROR,
        ex.getMessage()));
}
    }

}