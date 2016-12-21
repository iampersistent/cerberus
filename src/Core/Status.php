<?php
declare(strict_types = 1);

namespace Cerberus\Core;

class Status
{
    /** @var StatusCode */
    protected $statusCode;

    /** @var string */
    protected $statusMessage;

    public function __construct(StatusCode $statusCode, string $statusMessage = '')
    {
        $this->statusCode = $statusCode;
        $this->statusMessage = $statusMessage;
    }

    public static function createOk($message = ''): Status
    {
        return new Status(StatusCode::STATUS_CODE_OK(), $message);
    }

    public static function createProcessingError($message = ''): Status
    {
        return new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), $message);
    }

    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    public function getStatusMessage(): string
    {
        return $this->statusMessage;
    }

    public function isOk(): bool
    {
        return StatusCode::STATUS_CODE_OK() === $this->statusCode;
    }
}