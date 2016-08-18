<?php
declare(strict_types = 1);

namespace Cerberus\Core;

class Status
{
    /** @var StatusCode */
    protected $statusCode;

    /** @var string */
    protected $statusMessage;

    public function __construct(StatusCode $statusCode, string $statusMessage)
    {
        $this->statusCode = $statusCode;
        $this->statusMessage = $statusMessage;
    }

    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    public function getStatusMessage(): string
    {
        return $this->statusMessage;
    }

    public function isOk(): boolean
    {
        return StatusCode::STATUS_CODE_OK === $this->statusCode;
    }
}