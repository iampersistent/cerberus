<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use DateTime;
use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\Core\Exception\IllegalArgumentException;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\{
    Expression, ExpressionResult, ExpressionResultError, ExpressionResultSingle, PolicyDefaults
};

class AttributeValue extends Expression
{
    protected $dataTypeId;
    protected $value;

    protected $pipRequest;

    public function __construct($dataTypeId, $value = null)
    {
        if (func_num_args() === 1) {
            throw new IllegalArgumentException('If you need a null attribute value, it must be explicitly set');
        }

        $this->dataTypeId = $dataTypeId;
        $this->value = $value;
    }

    public function getDataTypeId()
    {
        return $this->dataTypeId;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults): ExpressionResult
    {
        if (! $this->validate()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Attribute type does not match value');

            return new ExpressionResultError($this->getStatus());
        }

        return new ExpressionResultSingle(new AttributeValue($this->getDataTypeId(), $this->getValue()));
    }

    protected function validateComponent(): bool
    {
        switch ($this->getDataTypeId()) {
            case DataTypeIdentifier::BOOLEAN:
                return is_bool($this->getValue());
            case DataTypeIdentifier::INTEGER:
                return is_int($this->getValue());
            case DataTypeIdentifier::STRING:
                return is_string($this->getValue());
            case DataTypeIdentifier::DATETIME:
                //TODO: verify
                return $this->getValue() instanceof DateTime;
            case DataTypeIdentifier::DOUBLE:
                return is_double($this->getValue());
            case DataTypeIdentifier::XPATH_EXPRESSION:
                return is_string($this->getValue());
            case DataTypeIdentifier::INDETERMINATE:
                //TODO: verify
                return true;
        }

        return false;
    }
}
