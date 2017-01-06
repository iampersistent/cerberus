<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\DataType\DataType;
use Cerberus\Core\Status;
use Cerberus\PDP\Policy\ConvertedArgument;
use Cerberus\PDP\Policy\FunctionDefinition;
use Ds\Map;
use Ds\Set;

abstract class FunctionDefinitionSimple extends FunctionDefinition
{
    /** @var int */
    protected $totalArgs;

    public function __construct($identifier, DataType $returnDataType, DataType $argsDataType, int $totalArgs)
    {
        parent::__construct($identifier, $returnDataType, $argsDataType, false);
        $this->totalArgs = $totalArgs;
    }

    public function validateArguments(Set $functionArguments, Map $convertedValues): Status
    {
        $listLengthStatus = $this->validateArgumentListLength($functionArguments);
        if (! $listLengthStatus->isOk()) {
            return $listLengthStatus;
        }

        foreach ($functionArguments as $index => $functionArgument) {
            $argument = new ConvertedArgument($functionArgument, $this->getDataTypeArgs(), false);
            if (! $argument->isOk()) {
                $decoratedStatus = new Status(
                    $argument->getStatus()->getStatusCode(),
                    $argument->getStatus()->getStatusMessage()." at arg index $index"
                );

                return $decoratedStatus;
            }
            $convertedValues->put($index, $argument->getValue());
        }

        return Status::createOk();
    }

    public function validateArgumentListLength(Set $functionArguments): Status
    {
        if ($functionArguments->count() !== $this->totalArgs) {

            return Status::createProcessingError(
                "Expected  $this->totalArgs arguments, got ".$functionArguments->count()
            );
        }

        return Status::createOk();
    }
}
