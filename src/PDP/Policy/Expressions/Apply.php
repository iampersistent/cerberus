<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Exception\FactoryException;
use Cerberus\PDP\Policy\Expression;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\Factory\FunctionDefinitionFactory;
use Cerberus\PDP\Policy\FunctionArgumentExpression;
use Cerberus\PDP\Policy\FunctionDefinition;
use Cerberus\PDP\Policy\PolicyDefaults;
use Ds\Set;

class Apply extends Expression
{
    protected $arguments;
    protected $description;
    protected $functionDefinition;
    /** @var FunctionDefinitionFactory */
    protected $functionDefinitionFactory;
    protected $functionId;

    public function __construct($functionId, $description = '')
    {
        $this->arguments = new Set();
        $this->description = $description;
        $this->functionId = $functionId;
    }

    public function addArgument($argument): self
    {
        $this->arguments->add($argument);

        return $this;
    }

    public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults): ExpressionResult
    {
        $this->functionDefinitionFactory = $evaluationContext->getFunctionDefinitionFactory();
        if (! $this->validate()) {
            return new ExpressionResultError($this->getStatus());
        }

        $functionDefinition = $this->getFunctionDefinition();
        if (! $functionDefinition) {
            return new ExpressionResultError(Status::createProcessingError("Unknown Function: $this->functionId"));
        }

        $functionArguments = new Set();
        foreach ($this->arguments as $argument) {
            $functionArguments->add(new FunctionArgumentExpression($argument,
                $evaluationContext, $policyDefaults));
        }
        $results = $functionDefinition->evaluate($evaluationContext, $functionArguments);

        return $results;
    }

    /**
     * @return FunctionDefinition|null
     */
    public function getFunctionDefinition()
    {
        if (! $this->functionDefinition && $this->functionId) {
            try {
                $this->functionDefinition = $this->functionDefinitionFactory->getFunctionDefinition($this->functionId);
            } catch (FactoryException $e) {
                $this->setStatus(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                    "FactoryException getting FunctionDefinition $this->functionId");
            }
        }

        return $this->functionDefinition;
    }

    protected function validateComponent():bool
    {
        if (! $this->functionId) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing FunctionId');

            return false;
        } else {
            $this->setStatus(StatusCode::STATUS_CODE_OK());

            return true;
        }
    }
}
