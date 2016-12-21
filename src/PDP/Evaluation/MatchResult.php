<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Evaluation;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;

class MatchResult
{
    protected $matchCode;

    protected $status;

    public function __construct(MatchCode $matchCode, Status $status = null)
    {
        $this->matchCode = $matchCode;
        $this->status = $status ?? Status::createOk('OK');
    }

    public static function createIndeterminate(Status $status = null): MatchResult
    {
        return new MatchResult(MatchCode::INDETERMINATE(), $status);
    }

    public static function createMatch(Status $status = null): MatchResult
    {
        return new MatchResult(MatchCode::MATCH(), $status);
    }

    public static function createNoMatch(Status $status = null): MatchResult
    {
        return new MatchResult(MatchCode::NO_MATCH(), $status);
    }

    public function getMatchCode(): MatchCode
    {
        return $this->matchCode;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}