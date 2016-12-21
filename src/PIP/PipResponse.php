<?php
declare(strict_types = 1);

namespace Cerberus\PIP;

use Cerberus\Core\Attribute;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Ds\Map;
use Ds\Set;

class PipResponse
{
    /** @var Attribute[]|Set */
    protected $attributes;
    /** @var bool */
    protected $simple = true;
    /** @var Status */
    protected $status;

    public function __construct($attributes = [], $pipRequest = null)
    {
        $this->attributes = new Set();
    }

    public function addAttribute(Attribute $attribute): self
    {
        /*
         * Determine if the simple status should be changed or not
         */
        if ($this->simple && ! $this->attributes->isEmpty()) {
            $this->simple = false;
        }
        $this->attributes->add($attribute);

        return $this;
    }

    public function addAttributes(Set $attributes): self
    {
        /*
         * Determine if the simple status should be changed or not
         */
        if ($this->simple && (! $this->attributes->isEmpty() || $attributes->count() > 1)) {
            $this->simple = false;
        }
        foreach ($attributes as $attribute) {
            $this->attributes->add($attribute);
        }

        return $this;
    }

    public function getAttributes(): Set
    {
        return $this->attributes;
    }

    public static function matches(PipRequest $pipRequest, Attribute $attribute): bool
    {
        if (! $pipRequest->getCategory() === $attribute->getCategory()) {
            return false;
        }
        if (! $pipRequest->getAttributeId() === $attribute->getAttributeId()) {
            return false;
        }
        if ($pipRequest->getIssuer() && $pipRequest->getIssuer() !== $attribute->getIssuer()) {
            return false;
        }

        return true;
    }

    public function matchingValues(PipRequest $pipRequest, Set $attributeValues)
    {
        if ($attributeValues->isEmpty()) {
            return $attributeValues;
        }

        /*
         * See if all of the values match the requested data type
         */
        $allMatch = true;
        foreach ($attributeValues as $attributeValue) {
            $allMatch = $attributeValue->getDataTypeId()->equals($pipRequest->getDataTypeId());
        }
        if ($allMatch) {
            return $attributeValues;
        }

        /*
         * If there was only one, return a null list
         */
        if ($attributeValues->count() === 1) {
            return null;
        }

        $attributeValuesMatching = new Set();
        foreach ($attributeValues as $attributeValue) {
            if ($attributeValue->getDataTypeId()->equals($pipRequest->getDataTypeId())) {
                $attributeValuesMatching->add($attributeValue);
            }
        }

        return $attributeValuesMatching->isEmpty() ? null : $attributeValuesMatching;
    }

    public function getMatchingResponse(PipRequest $pipRequest, PipResponse $pipResponse): PipResponse
    {
        if (! $pipResponse->getStatus()->isOk() || $pipResponse->getAttributes()->isEmpty()) {
            return $pipResponse;
        }

        if ($pipResponse->isSimple()) {
            /*
             * Get the one Attribute and verify that it matches the request, and that the data type of its
             * values matches the request
             */
            $attributeResponse = $pipResponse->getAttributes()->next();
            if (matches($pipRequest, $attributeResponse)) {
                $attributeValues = $attributeResponse->getValues();
                if (! $attributeValues || $attributeValues->isEmpty()) {
                    return $pipResponse;
                } else {
                    $attributeValueResponse = $attributeResponse->getValues()->next();
                    if ($attributeValueResponse->getDataTypeId()->equals($pipRequest->getDataTypeId())) {
                        return $pipResponse;
                    } else {
                        return $this->generateOkResponse();
                    }
                }
            } else {
                return $this->generateOkResponse();
            }
        } else {
            /*
             * Iterate over the Attributes and just get the ones that match and collapse any matching
             * AttributeValues
             */
            $attributeMatch = null;
            foreach ($pipResponse->getAttributes() as $attributeResponse) {
                if ($this->matches($pipRequest, $attributeResponse)) {
                    /*
                     * Get subset of the matching $attribute values
                     */
                    $attributeValuesMatch = $this->matchingValues($pipRequest,
                        $attributeResponse->getValues());
                    if ($attributeValuesMatch && ! $attributeValuesMatch->isEmpty()) {
                        if (! $attributeMatch) {
                            $attributeMatch = new Attribute($pipRequest->getCategory(),
                                $pipRequest->getAttributeId(),
                                $attributeValuesMatch,
                                $pipRequest->getIssuer(), false);
                        } else {
                            $attributeMatch->addValues($attributeValuesMatch);
                        }
                    }
                }
            }
            if (! $attributeMatch) {
                return $this->generateOkResponse();
            } else {
                return new PipResponse($attributeMatch);
            }
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(Status $status = null): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Takes a list of simple Attributes and collapses $attributes with the same category, id, value data type,
     * and issuer into a single Attribute and returns the list of collapsed Attributes.
     *
     * @param $attributes
     *
     * @return
     */
    protected function collapseAttributes(Set $attributes)
    {
        if ($attributes->isEmpty()) {
            return $attributes;
        }
        $map = new Map();
        foreach ($attributes as $attribute) {
            $pipRequest = new PipRequest($attribute);
            if ($map->hasKey($pipRequest)) {
                $map->get($pipRequest)->addValues($attribute->getValues());
            } else {
                $map->put($pipRequest, new Attribute($attribute));
            }
        }

        return $map->isEmpty() ? null : $map->values();
    }

    /**
     * Takes a {@link org.apache.openaz.xacml.api.pip.PipResponse} that may contain
     * {@link org.apache.openaz.xacml.api.Attribute}s, with multiple identifiers, each of which may contain
     * multiple {@link org.apache.openaz.xacml.api.AttributeValue}s with different data types and creates a
     * collection of simple <code>PipResponse</code>s that contain a single <code>Attribute</code> with
     * <code>AttributeValue</code>s of one data type.
     *
     * @param $pipResponse the <code>PipResponse</code> to split
     *
     * @return a <code>Collection</code> of simple <code>PipResponse</code>s
     * @throws org.apache.openaz.xacml.api.pip.PIPException if there is an error splitting the response
     */
    public function splitResponse(PipResponse $pipResponse): Map
    {
        $map = new Map();
        if (! $pipResponse->getStatus()->isOk() || $pipResponse->isSimple()) {
            $map->put(new PipRequest($pipResponse->getAttributes()->next()), $pipResponse);
        } else {
            $allAttributesSimple = new Set();
            foreach ($pipResponse->getAttributes() as $attribute) {
                $attributesSplit = $this->simplifyAttribute($attribute);
                if ($attributesSplit && ! $attributesSplit->isEmpty()) {
                    $allAttributesSimple->addAll($attributesSplit);
                }
            }
            if (! $allAttributesSimple->isEmpty()) {
                $attributesCollapsed = $this->collapseAttributes($allAttributesSimple);
                foreach ($attributesCollapsed as $attributeCollapsed) {
                    $map->put(new PipRequest($attributeCollapsed), new PipResponse($attributeCollapsed));
                }
            }
        }

        return $map;
    }

    protected function generateOkResponse()
    {
        return new PipResponse(Status::createOk());
    }

    /**
     * Splits an Attribute that may contain multiple data types into a list of Attributes, each with only one
     * data type
     *
     * @param $attribute
     *
     * @return
     */
    protected function simplifyAttribute(Attribute $attribute)
    {
        $listAttributes = new Set();
        if ($attribute->getValues()->isEmpty()) {
            $listAttributes->add($attribute);
        } else {
            foreach ($attribute->getValues() as $attributeValue) {
                $listAttributes->add(new Attribute($attribute->getCategory(), $attribute->getAttributeId(),
                    $attributeValue, $attribute->getIssuer(), $attribute
                        ->getIncludeInResults()));
            }

            return $listAttributes;
        }
    }
}