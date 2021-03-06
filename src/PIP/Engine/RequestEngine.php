<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Engine;

use Cerberus\Core\Attribute;
use Cerberus\Core\Request;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PIP\Contract\PipEngine;
use Cerberus\PIP\PipFinder;
use Cerberus\PIP\PipRequest;
use Cerberus\PIP\PipResponse;
use Ds\Set;

class RequestEngine implements PipEngine
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    public function getName(): string
    {
        return static::class;
    }

    public function getDescription(): string
    {
        return 'PipEngine for retrieving Attributes from the Request';
    }

    public function getAttributes(PipRequest $pipRequest, PipFinder $pipFinder = null): PipResponse
    {
        if (! $this->request) {
            return (new PipResponse())->setStatus(Status::createOk());
        }

        $requestAttributes = $this->request->getRequestAttributes($pipRequest->getCategory());
        if ($requestAttributes->isEmpty()) {
            return (new PipResponse())->setStatus(Status::createOk());
        }

        $pipResponse = new PipResponse();
        foreach ($requestAttributes as $requestAttribute) {
            $attributes = $requestAttribute->getAttributes($pipRequest->getAttributeId());
            if (! $attributes->getValues()->isEmpty()
                && $pipRequest->getIssuer() === $attributes->getIssuer()
            ) {
                /*
                 * If all of the attribute values in the given Attribute match the requested data type, we
                 * can just return the whole Attribute as part of the response.
                 */
                $allMatch = true;
                foreach ($attributes->getValues() as $attributeValue) {
                    if ($pipRequest->getDataTypeId() !== $attributeValue->getDataTypeId()) {
                        $allMatch = false;
                        break;
                    }
                }
                if ($allMatch) {
                    $pipResponse->addAttribute($attributes);
                } else {
                    /*
                    * Only a subset of the values match, so we have to construct a new Attribute
                    * containing only the matching values.
                    */
                    $attributeValues = new Set();
                    foreach ($attributes->getValues() as $attributeValue) {
                        if ($pipRequest->getDataTypeId() === $attributeValue->getDataTypeId()) {
                            $attributeValues->add($attributeValue);
                        }
                    }
                    if (! $attributeValues->isEmpty()) {
                        $pipResponse->addAttribute(new Attribute($attributes->getCategory(),
                            $attributes->getAttributeId(),
                            $attributeValues, $attributes->getIssuer(), $attributes->getIncludeInResults()));
                    }
                }
            }
        }

        return $pipResponse;
    }

    public function attributesRequired()
    {
        return;//Collections.emptyList();
    }

    public function attributesProvided()
    {
        $providedAttributes = new Set();
        foreach ($this->request->getRequestAttributes() as $attributes) {
            foreach ($attributes->getAttributes() as $attribute) {
                $datatypes = new Set();
                foreach ($attribute->getValues() as $value) {
                    $datatypes->add($value->getDataTypeId());
                }

                foreach ($datatypes as $datatype) {
                    $providedAttributes->add(new PipRequest($attribute->getCategory(), $attribute
                        ->getAttributeId(), $datatype, $attribute->getIssuer()));
                }
            }
        }

        return $providedAttributes;
    }
}