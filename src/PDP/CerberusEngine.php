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

    public function __construct(PolicyFinder $policyFinder, PipFinder $pipFinder, $functionDefinitionFactory)
    {
        $this->evaluationContextFactory = new EvaluationContextFactory($policyFinder, $pipFinder, $functionDefinitionFactory);
        $this->pipFinder = $pipFinder;
        $this->policyFinder = $policyFinder;
        $this->scopeResolver = new ScopeResolver();
    }

    public function decide(Request $request): Response
    {
        /*
         * Validate the request
         */
        $statusRequest = $request->getStatus();
        if ($statusRequest && ! $statusRequest->isOk()) {
            return new Response($statusRequest);
        }

        /*
         * Split the original request up into individual decision requests
         */
        $individualDecisionRequestGenerator = new IndividualDecisionRequestGenerator($this->scopeResolver, $request);

        /*
         * Determine if we are combining multiple $results into a single $result
         */
        $combineResults = $request->getCombinedDecision(); // boolean
        $resultsCombined = null; // Result

        /*
         * Iterate over all of the individual decision requests and process them, combining them into the
         * final response
         */
        $response = new Response();

        $requestsIndividualDecision = $individualDecisionRequestGenerator->getIndividualDecisionRequests();
        if ($requestsIndividualDecision->isEmpty()) {
            return new Response(Status::createProcessingError('No individual decision requests'));
        }

        foreach ($requestsIndividualDecision as $individualDecision) {
            $status = $individualDecision->getStatus();
            if ($status && ! $status->isOk()) {
                $individualDecisionResult = new Result();
            } else {
                $evaluationContext = $this->evaluationContextFactory->getEvaluationContext($individualDecision); // EvaluationContext
                if (!$evaluationContext) {
                    $individualDecisionResult = new Result(Status::createProcessingError('Null EvaluationContext'));
                } else {
                    $individualDecisionResult = $this->processRequest($evaluationContext);
                }
            }

            if ($combineResults) {
                $decision = $individualDecisionResult->getDecision(); // Decision
                $status = $individualDecisionResult->getStatus(); // Status
                if (! $individualDecisionResult->getAssociatedAdvice()->isEmpty()) {
                    $decision = Decision::INDETERMINATE();
                    $status = Status::createProcessingError('Advice not allowed in combined decision');
                } else {
                    if (! $individualDecisionResult->getObligations()->isEmpty()) {
                        $decision = Decision::INDETERMINATE();
                        $status = Status::createProcessingError('Obligations not allowed in combined decision');
                    }
                }

                /** @var Result $resultsCombined */
                if (! $resultsCombined) {
                    $resultsCombined = new Result($decision, $status);
                } else {
                    if ($resultsCombined->getDecision() !== $individualDecisionResult->getDecision()) {
                        $resultsCombined->setDecision(Decision::INDETERMINATE());
                        $resultsCombined->setStatus(Status::createProcessingError(
                            'Individual decisions do not match'));
                    }
                }
                $resultsCombined->addPolicyIdentifiers($individualDecisionResult->getPolicyIdentifiers());
                $resultsCombined->addPolicySetIdentifiers($individualDecisionResult->getPolicySetIdentifiers());
                $resultsCombined->addAttributeCategories($individualDecisionResult->getAttributes());
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
            if ($policyFinderResult->getStatus() && ! $policyFinderResult->getStatus()->isOk()) {
                return new Result($policyFinderResult->getStatus());
            }

            /** @var PolicyDef $policyDefRoot */
            $policyDefRoot = $policyFinderResult->getPolicyDef();
            if (! $policyDefRoot) {
                switch ($this->defaultDecision) {
                    case Decision::DENY:
                    case Decision::NOT_APPLICABLE:
                    case Decision::PERMIT:
                        return new Result($this->defaultDecision, Status::createOk('No applicable policy'));
                    case Decision::INDETERMINATE:
                    case Decision::INDETERMINATE_DENY:
                    case Decision::INDETERMINATE_DENY_PERMIT:
                    case Decision::INDETERMINATE_PERMIT:
                        return new Result($this->defaultDecision, Status::createProcessingError('No applicable policy'));
                }
            }
            /** @var Result $result */
            $result = $policyDefRoot->evaluate($evaluationContext);
            if ($result->getStatus()->isOk()) {
                $requestAttributesIncludeInResult = $evaluationContext->getRequest()->getRequestAttributesIncludedInResult(); //Collection < AttributeCategory>
                if ($requestAttributesIncludeInResult && ! $requestAttributesIncludeInResult->isEmpty()) {
                    $result = new Result();
                    $result->addAttributeCategories($requestAttributesIncludeInResult);
                }
            }

            return $result;
        } catch (EvaluationException $e) {
            return new Result(Status::createProcessingError($e->getMessage()));
        }
    }
}