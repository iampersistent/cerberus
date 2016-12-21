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
            $matchData = $match['match'];
            $attributeValue = new AttributeValue(
                $matchData['attributeValue']['dataType'],
                $matchData['attributeValue']['text']
            );
            $attributeBase = new AttributeDesignator(
                $matchData['attributeDesignator']['category'],
                $matchData['attributeDesignator']['dataType'],
                $matchData['attributeDesignator']['mustBePresent'],
                $matchData['attributeDesignator']['attributeId']
            );
            $policyDefaults = new PolicyDefaults();
            $this->matches[] = new Match(
                $matchData['matchId'],
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
            switch ($matchResultMatch->getMatchCode()->getValue()) {
                case MatchCode::INDETERMINATE:
                    if (! $matchResultFallThrough->getMatchCode()->is(MatchCode::INDETERMINATE)) {
                        $matchResultFallThrough = $matchResultMatch;
                    }
                    break;
                case MatchCode::MATCH:
                    break;
                case MatchCode::NO_MATCH:
                    return $matchResultMatch;
            }
        }

        return $matchResultFallThrough;
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