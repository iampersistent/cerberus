<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Exception\DataTypeException;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, MatchCode, MatchResult
};
use Cerberus\PDP\Exception\FactoryException;
use Cerberus\PDP\Policy\Expressions\AttributeRetrievalBase;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class Match implements Matchable
{
    use PolicyComponent;

    protected $attributeValue;
    /** @var AttributeRetrievalBase */
    protected $attributeBase;
    protected $functionDefinition;
    protected $functionDefinitionFactory;
    protected $matchId;
    protected $policyDefaults;

    public function __construct(
        $matchId,
        AttributeValue $attributeValue,
        AttributeRetrievalBase $attributeBase,
        PolicyDefaults $policyDefaults
    ) {
        $this->attributeBase = $attributeBase;
        $this->attributeValue = $attributeValue;
        $this->matchId = $matchId;
        $this->policyDefaults = $policyDefaults;
    }

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        $this->functionDefinitionFactory = $evaluationContext->getFunctionDefinitionFactory();
        if (! $this->validate()) {
            return new MatchResult(MatchCode::INDETERMINATE(), $this->getStatus());
        }

        $functionDefinitionMatch = $this->getFunctionDefinition();
        //assert functionDefinitionMatch != null;

        $functionArgument1 = new FunctionArgumentAttributeValue($this->attributeValue); // FunctionArgument

        /** @var ExpressionResult $expressionResult */
        $expressionResult = $this->attributeBase->evaluate($evaluationContext, $this->policyDefaults);

        if (! $expressionResult->isOk()) {
            return new MatchResult($expressionResult->getStatus());
        }

        if ($expressionResult->isBag()) {
            $matchResult = new MatchResult(MatchCode::NO_MATCH());
            $bagAttributeValues = $expressionResult->getBag(); // Bag
            if ($bagAttributeValues) {
                $attributeValues = $bagAttributeValues->getAttributeValues(); // AttributeValue
                foreach ($attributeValues as $attributeValue) {
                    if (! $matchResult->getMatchCode()->is(MatchCode::MATCH)) {
                        break;
                    }

                    $matchResultValue = $this->processMatch(
                        $evaluationContext,
                        $functionDefinitionMatch,
                        $functionArgument1,
                        new FunctionArgumentAttributeValue($attributeValue)
                    );
                    switch ($matchResultValue->getMatchCode()) {
                        case MatchCode::INDETERMINATE:
                            if ($matchResult->getMatchCode() != MatchCode::INDETERMINATE) {
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
        } else {
            /*
             * There is a single value, so add it as the second argument and do the one function evaluation
             */
            $attributeValueExpressionResult = $expressionResult->getValue(); // AttributeValue
            if (! $attributeValueExpressionResult) {
                return new MatchResult(MatchCode::INDETERMINATE(),
                    new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                        'Null AttributeValue'));
            }

            return $this->processMatch($evaluationContext, $functionDefinitionMatch, $functionArgument1,
                new FunctionArgumentAttributeValue($attributeValueExpressionResult));
        }
    }

    /**
     * @return MatchResult
     */
    protected function processMatch(
        EvaluationContext $evaluationContext,
        FunctionDefinition $functionDefinition,
        FunctionArgument ...$args
    ): MatchResult
    {
//        List<FunctionArgument> listArguments = new ArrayList<FunctionArgument>(2);
//        listArguments.add(arg1);
//        listArguments.add(arg2);

        $expressionResult = $functionDefinition->evaluate($evaluationContext, $args); // ExpressionResult
        if (! $expressionResult->isOk()) {
            return new MatchResult(MatchCode::INDETERMINATE(), $expressionResult->getStatus());
        }

        try {
            $attributeValueResult = (bool)$expressionResult->getValue();
        } catch (DataTypeException $e) {
            return new MatchResult(MatchCode::INDETERMINATE(),
                new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), $e->getMessage()));
        }
        if (! $attributeValueResult) {
            return new MatchResult(MatchCode::INDETERMINATE(), new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                'Non-boolean result from Match Function ' .
                $functionDefinition->getId() . ' on ' .
                $expressionResult->getValue()->toString()));
        } else {
            if ($attributeValueResult->getValue()->booleanValue()) {
                return new MatchResult(MatchCode::MATCH());
            } else {
                return new MatchResult(MatchCode::NO_MATCH());
            }
        }

    }

    protected function getFunctionDefinition(): FunctionDefinition
    {
        if (! $this->functionDefinition && $this->matchId) {
            try {
                $this->functionDefinition = $this->functionDefinitionFactory->getFunctionDefinition($this->matchId);
            } catch (FactoryException $e) {
                $this->setStatus(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                    'FactoryException getting FunctionDefinition');
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

        $this->setStatus(StatusCode::STATUS_CODE_OK(), null);

        return true;
    }
}