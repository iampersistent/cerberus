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

    public function getStatusCode()
    {
        return $this->status ? $this->status->getStatusCode() : null;
    }

    public function getStatusMessage()
    {
        return $this->status ? $this->status->getStatusMessage() : null;
    }

    public function isOk(): boolean
    {
        return $this->getStatusCode() === StatusCode::STATUS_CODE_OK;
    }

    public function validate(): boolean
    {
        if (null === $this->getStatusCode()) {
            return $this->validateComponent();
        } else {
            return $this->isOk();
        }
    }
}