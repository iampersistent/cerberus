<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class Condition
{
    use PolicyComponent;

    /** @var Expression */
    protected $expression;

    public function __construct($data)
    {
        // function stuff
        $todo = true;
    }

    public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults){
        if (! $this->validate()) {
            return new ExpressionResultBoolean($this->getStatus());
        }

/*
 * Evaluate the expression
 */
$expressionResult = $this->getExpression()->evaluate($evaluationContext, $policyDefaults);

        if (!$expressionResult->isOk()) {
            return new ExpressionResultBoolean($expressionResult->getStatus());
        }

        /*
         * Ensure the result is a single element of type boolean
         */
        if ($expressionResult->isBag()) {
            return ERB_RETURNED_BAG;
        }

        if (!$attributeValueResult = $expressionResult->getValue()) {
        return ERB_RETURNED_NULL;
        } else if (!DataTypes.DT_BOOLEAN.getId().equals($attributeValueResult->getDataTypeId())) {
        return ERB_RETURNED_NON_BOOLEAN;
        }

        /*
        * Otherwise it is a valid condition evaluation
        */
//        Boolean booleanValue = null;
//        try {
//        booleanValue = DataTypes.DT_BOOLEAN.convert($attributeValueResult->getValue());
//        } catch (DataTypeException ex) {
//        return new ExpressionResultBoolean(new StdStatus(StdStatusCode.STATUS_CODE_PROCESSING_ERROR,
//        ex.getMessage()));
//        }
//
//        if (booleanValue == null) {
//        return ERB_INVALID_BOOLEAN;
//        } else {
//        return (booleanValue.booleanValue()
//        ? ExpressionResultBoolean.ERB_TRUE : ExpressionResultBoolean.ERB_FALSE);
//        }
    }
}