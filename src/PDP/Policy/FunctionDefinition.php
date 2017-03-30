<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\DataType\DataType;
use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Ds\Set;

abstract class FunctionDefinition
{
    const ANY_OF_ANY = 'function:any-of-any';
    const BOOLEAN_ALL_OF = 'function:boolean-all-of';
    const BOOLEAN_EQUAL = 'function:boolean-equal';
    const INTEGER_BAG = 'function:integer-bag';
    const INTEGER_EQUAL = 'function:integer-equal';
    const STRING_BAG = 'function:string-bag';
    const STRING_EQUAL = 'function:string-equal';
    const STRING_IS_IN = 'function:string-is-in';
    const STRING_ONE_AND_ONLY = 'function:string-one-and-only';

    protected $argsDataType;
    protected $id;
    protected $returnsBag;
    protected $returnDataType;

    public function __construct($identifier, DataType $returnDataType, DataType $argsDataType, bool $returnsBag)
    {
        $this->argsDataType = $argsDataType;
        $this->id = $identifier;
        $this->returnsBag = $returnsBag;
        $this->returnDataType = $returnDataType;
    }

    public function getDataType(): DataType
    {
        return $this->returnDataType;
    }

    public function getDataTypeId(): DataTypeIdentifier
    {
        return $this->returnDataType->getType();
    }

    public function getDataTypeArgs(): DataType
    {
        return $this->argsDataType;
    }

    public function returnsBag(): bool
    {
        return $this->returnsBag;
    }

    public function getFunctionStatus(Status $originalStatus): Status
    {
        return new Status(
            $originalStatus->getStatusCode(),
            $this->getShortFunctionId() . ' ' . $originalStatus->getStatusMessage()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getShortFunctionId()
    {
        $parts = explode(':', $this->id);

        return $parts[1];
    }

    public function getShortDataTypeId($identifier)
    {
        return (string) $identifier;
    }

    abstract public function evaluate(
        EvaluationContext $evaluationContext,
        Set $arguments
    ): ExpressionResult;
}
