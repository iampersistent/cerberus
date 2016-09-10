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
        $this->status = $status ?? new Status(StatusCode::STATUS_CODE_OK(), 'OK');
    }

    public static function createMatch(): MatchResult
    {
        return new MatchResult(MatchCode::MATCH());
    }

    public static function createNoMatch(): MatchResult
    {
        return new MatchResult(MatchCode::NO_MATCH());
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