<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Exception\DataTypeException;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, MatchCode, MatchResult
};
use Cerberus\PDP\Exception\FactoryException;
use Cerberus\PDP\Policy\Expressions\AttributeRetrievalBase;
use Cerberus\PDP\Policy\Traits\PolicyComponent;
use Ds\Set;

class Match implements Matchable, PolicyElement
{
    use PolicyComponent;

    protected $attributeValue;
    /** @var AttributeRetrievalBase */
    protected $attributeBase;
    protected $functionDefinition;
    protected $functionDefinitionFactory;
    protected $matchId;
    protected $policyDefaults;

    public function __construct($matchId)
    {
        $this->matchId = $matchId;
        $this->policyDefaults = new PolicyDefaults();
    }

    public function setAttributeBase(AttributeRetrievalBase $attributeBase)
    {
        $this->attributeBase = $attributeBase;
    }

    public function setAttributeValue(AttributeValue $attributeValue)
    {
        $this->attributeValue = $attributeValue;
    }

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        $this->functionDefinitionFactory = $evaluationContext->getFunctionDefinitionFactory();
        if (! $this->validate()) {
            return MatchResult::createIndeterminate($this->getStatus());
        }

        /** @var ExpressionResult $expressionResult */
        $expressionResult = $this->attributeBase->evaluate($evaluationContext, $this->policyDefaults);
        if (! $expressionResult->isOk()) {
            return MatchResult::createIndeterminate($expressionResult->getStatus());
        }

        $functionDefinitionMatch = $this->getFunctionDefinition();
        $functionArgument1 = new FunctionArgumentAttributeValue($this->attributeValue);

        if ($expressionResult->isBag()) {
            $matchResult = MatchResult::createNoMatch();
            $bagAttributeValues = $expressionResult->getBag();
            if ($bagAttributeValues) {
                $attributeValues = $bagAttributeValues->getAttributeValues();
                foreach ($attributeValues as $attributeValue) {
                    $matchResultValue = $this->processMatch(
                        $evaluationContext,
                        $functionDefinitionMatch,
                        $functionArgument1,
                        new FunctionArgumentAttributeValue($attributeValue)
                    );
                    switch ($matchResultValue->getMatchCode()->getValue()) {
                        case MatchCode::INDETERMINATE:
                            if (! $matchResult->getMatchCode()->is(MatchCode::INDETERMINATE)) {
                                $matchResult = $matchResultValue;
                            }
                            break;
                        case MatchCode::MATCH:
                            $matchResult = $matchResultValue;
                            break;
                        case MatchCode::NO_MATCH:
                            break;
                    }
                }
            }

            return $matchResult;
        }

        /*
         * There is a single value, so add it as the second argument and do the one function evaluation
         */
        $attributeValueExpressionResult = $expressionResult->getValue(); // AttributeValue
        if (! $attributeValueExpressionResult) {
            return MatchResult::createIndeterminate(Status::createProcessingError('Null AttributeValue'));
        }

        return $this->processMatch($evaluationContext, $functionDefinitionMatch, $functionArgument1,
            new FunctionArgumentAttributeValue($attributeValueExpressionResult));
    }

    protected function processMatch(
        EvaluationContext $evaluationContext,
        FunctionDefinition $functionDefinition,
        FunctionArgument ...$arguments
    ): MatchResult
    {
        $expressionResult = $functionDefinition->evaluate($evaluationContext, new Set($arguments));
        if (! $expressionResult->isOk()) {
            return MatchResult::createIndeterminate($expressionResult->getStatus());
        }

        try {
            $attributeValueResult = $expressionResult->getValue();
        } catch (DataTypeException $e) {
            return MatchResult::createIndeterminate(
                Status::createProcessingError($e->getMessage()));
        }
        if (! $attributeValueResult) {
            return MatchResult::createIndeterminate(Status::createProcessingError(
                'Non-boolean result from Match Function ' .
                $functionDefinition->getId() . ' on ' .
                $expressionResult->getValue()->toString()));
        }
        if ($attributeValueResult->getValue()) {
            return MatchResult::createMatch();
        }

        return MatchResult::createNoMatch();
    }

    protected function getFunctionDefinition(): FunctionDefinition
    {
        if (! $this->functionDefinition && $this->matchId) {
            try {
                $this->functionDefinition = $this->functionDefinitionFactory->getFunctionDefinition($this->matchId);
            } catch (FactoryException $e) {
                $this->setStatus(StatusCode::STATUS_CODE_PROCESSING_ERROR(), 'FactoryException getting FunctionDefinition');
            }
        }

        return $this->functionDefinition;
    }

    protected function validateComponent(): bool
    {
        if (! $this->attributeValue) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing AttributeValue');

            return false;
        }
        if (! $this->matchId) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing MatchId');

            return false;
        }
        if (! $functionDefinition = $this->getFunctionDefinition()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Unknown MatchId '
                . $this->matchId . '\'');

            return false;
        }
        if ($functionDefinition->returnsBag()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'FunctionDefinition returns a bag');

            return false;
        }
//        if (! $functionDefinition->getDataTypeId()
//            || ! $functionDefinition->getDataTypeId()->equals(XACML::ID_DATATYPE_BOOLEAN)
//        ) {
//            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(),
//                'Non-Boolean return type for FunctionDefinition');
//
//            return false;
//        }
        if (! $this->attributeBase) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(),
                'Missing AttributeSelector or AttributeDesignator');

            return false;
        }

        $this->setStatus(StatusCode::STATUS_CODE_OK());

        return true;
    }
}
