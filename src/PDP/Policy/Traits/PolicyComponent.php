<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Traits;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;

trait PolicyComponent
{
    /** @var Status */
    protected $status;

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(StatusCode $statusCode, string $message = null)
    {
        $this->status = new Status($statusCode, $message);
    }

    public function getStatusCode()
    {
        return $this->status ? $this->status->getStatusCode() : null;
    }

    public function getStatusMessage(): string
    {
        return $this->status ? $this->status->getStatusMessage() : '';
    }

    public function isOk(): bool
    {
        return $this->getStatusCode() === StatusCode::STATUS_CODE_OK();
    }

    public function validate(): bool
    {
        if (null === $this->getStatusCode()) {
            return $this->validateComponent();
        } else {
            return $this->isOk();
        }
    }
}