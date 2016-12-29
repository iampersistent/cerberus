<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Decision;

class RuleEffect
{
    protected $decision;
    protected $name;

    public function __construct(string $name, Decision $decision)
    {
        $this->decision = $decision;
        $this->name = $name;
    }

    public function getDecision(): Decision
    {
        return $this->decision;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function getRuleEffect($effectName): RuleEffect
    {
        switch (strtolower($effectName)) {
            case 'deny':
                return new RuleEffect('Deny', Decision::DENY());
            case 'permit':
                return new RuleEffect('Permit', Decision::PERMIT());
            default:
                return null;
        }
    }

}