<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\StatusCode;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class VariableDefinition
{
    use PolicyComponent;

    protected $expression;
    protected $id;

    public function __construct(string $variableId)
    {
        $this->id = $variableId;
    }

    public function getExpression(): Expression
    {
        return $this->expression;
    }

    public function setExpression(Expression $expression)
    {
        $this->expression = $expression;
    }

    public function getId(): string
    {
        return $this->id;
    }

    protected function validateComponent(): bool
    {
        if (! $this->getId()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing variable id');

            return false;
        }
        if (! $this->getExpression()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing variable expression');

            return false;
        }
        $this->setStatus(StatusCode::STATUS_CODE_OK());

        return true;
    }
}