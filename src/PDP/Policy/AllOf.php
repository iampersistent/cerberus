<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, MatchCode, MatchResult
};
use Cerberus\PDP\Policy\Expressions\AttributeDesignator;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class AllOf implements Matchable
{
    use PolicyComponent;

    /** @var Match[] */
    protected $matches;

    public function __construct($matches)
    {
        foreach ($matches as $match) {
            $attributeValue = new AttributeValue(
                $match['attributeValue']['dataType'],
                $match['attributeValue']['text']
            );
            $attributeBase = new AttributeDesignator(
                $match['attributeDesignator']['category'],
                $match['attributeDesignator']['dataType'],
                $match['attributeDesignator']['mustBePresent']
            );
            $policyDefaults = new PolicyDefaults();
            $this->matches[] = new Match(
                $match['matchId'],
                $attributeValue,
                $attributeBase,
                $policyDefaults
            );
        }
    }

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (! $this->validate()) {
            return new MatchResult(MatchCode::INDETERMINATE(),
                new Status($this->getStatusCode(), $this->getStatusMessage()));
        }

        $matchResultFallThrough = MatchResult::createMatch();
        foreach ($this->matches as $match) {
            $matchResultMatch = $match->match($evaluationContext);
            switch ((string)$matchResultMatch->getMatchCode()) {
                case MatchCode::INDETERMINATE:
                    if ($matchResultFallThrough->getMatchCode() != MatchCode::INDETERMINATE()) {
                        $matchResultFallThrough = $matchResultMatch;
                    }
                    break;
                case MatchCode::MATCH:
                    break;
                case MatchCode::NO_MATCH:
                    return $matchResultMatch;
            }
        }

        return MatchResult::createMatch();
    }

    protected function validateComponent(): bool
    {
        if (0 === count($this->matches)) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing matches');

            return false;
        } else {
            $this->setStatus(StatusCode::STATUS_CODE_OK());

            return true;
        }
    }

}