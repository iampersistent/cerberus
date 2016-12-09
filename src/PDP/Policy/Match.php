<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Exception\DataTypeException;
use Cerberus\Core\Status;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, MatchCode, MatchResult
};
use Cerberus\PDP\Policy\Expressions\AttributeRetrievalBase;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class Match implements Matchable
{
    use PolicyComponent;

    protected $attributeValue;
    protected $attributeBase;
    protected $matchId;
    protected $policyDefaults;

    public function __construct($matchId, AttributeValue $attributeValue, AttributeRetrievalBase $attributeBase, PolicyDefaults $policyDefaults)
    {

    }

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (!$this->validate()) {
            return new MatchResult(MatchCode::INDETERMINATE(), $this->getStatus());
        }

 $functionDefinitionMatch = $this->getFunctionDefinition();
        //assert functionDefinitionMatch != null;

        $attributeValue = $this->getAttributeValue();
 $functionArgument1 = new FunctionArgumentAttributeValue(attributeValue); // FunctionArgument

    $attributeRetrievalBase = $this->getAttributeRetrievalBase();

        /** @var ExpressionResult $expressionResult */
 $expressionResult = $attributeRetrievalBase->evaluate(evaluationContext,
$this->getPolicyDefaults());

        if (!$expressionResult->isOk()) {
return new MatchResult($expressionResult->getStatus());
}

if ($expressionResult->isBag()) {
 $matchResult = new MatchResult(MatchCode::NO_MATCH());
 $bagAttributeValues = $expressionResult->getBag(); // Bag
if ($bagAttributeValues != null) {
 $attributeValues = $bagAttributeValues->getAttributeValues(); // AttributeValue
    while ($matchResult->getMatchCode() != MatchCode::MATCH
        && iterAttributeValues.hasNext()) {
         $matchResultValue = $this->processMatch($evaluationContext,
            functionDefinitionMatch,
            functionArgument1,
            new FunctionArgumentAttributeValue(
                iterAttributeValues
                .next()));
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
                      if ($attributeValueExpressionResult == null) {
                      return new MatchResult(MatchCode::INDETERMINATE, new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
"Null AttributeValue"));
}

return $this->processMatch(evaluationContext, functionDefinitionMatch, functionArgument1,
new FunctionArgumentAttributeValue(attributeValueExpressionResult));
    }

/**
 * @return MatchResult
 */
    protected function processMatch(EvaluationContext $evaluationContext,
                                     FunctionDefinition $functionDefinition, FunctionArgument $arg1,
                                     FunctionArgument $arg2): MatchResult {
//        List<FunctionArgument> listArguments = new ArrayList<FunctionArgument>(2);
//        listArguments.add(arg1);
//        listArguments.add(arg2);

         $expressionResult = $functionDefinition->evaluate($evaluationContext, $listArguments); // ExpressionResult
        if (!$expressionResult.isOk()) {
            return new MatchResult(MatchCode::INDETERMINATE(), $expressionResult->getStatus());
        }

        AttributeValue<Boolean> attributeValueResult = null;
        try {
            $attributeValueResult = DataTypes.DT_BOOLEAN.convertAttributeValue($expressionResult->getValue());
        } catch (DataTypeException $e) {
            return new MatchResult(MatchCode::INDETERMINATE(), new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), $e->getMessage()));
        }
        if ($attributeValueResult == null) {
            return new MatchResult(new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                "Non-boolean result from Match Function "
                + $functionDefinition.getId() + " on "
                + $expressionResult.getValue().toString()));
        } else if ($attributeValueResult.getValue().booleanValue()) {
            return MatchResult.MM_MATCH;
        } else {
            return MatchResult.MM_NOMATCH;
        }

    }

    /**
     * @return AttributeRetrievalBase
     */
    protected function getAttributeRetrievalBase()
    {

    }

    /**
     * @return AttributeValue
     */
    protected function getAttributeValue()
    {
    }

    /**
     * @return FunctionDefinition
     */
    protected function getFunctionDefinition()
    {
    }
}