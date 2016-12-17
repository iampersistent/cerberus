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

    public function __construct()
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

    /**
     * Determines if the given {@link org.apache.openaz.xacml.api.pip.PipRequest} matches the given
     * {@link org.apache.openaz.xacml.api.Attribute} by comparing the category, $attribute id, and if not null
     * in the <code>PipRequest</code>, the issuer.
     *
     * @param $pipRequest the <code>PipRequest</code> to compare against
     * @param $attribute the <code>Attribute</code> to compare to
     *
     * @return true if the <code>Attribute</code> matches the <code>PipRequest</code> else false
     */
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

    /**
     * Gets the subset of the {@link org.apache.openaz.xacml.api.AttributeValue}s from the given
     * <code>Collection</code> whose data type matches the data type in the given
     * {@link org.apache.openaz.xacml.api.pip.PipRequest}.
     *
     * @param $pipRequest the <code>PipRequest</code> to compare against
     * @param $listAttributeValues the <code>Collection</code> of <code>AttributeValue</code>s to select from
     *
     * @return a <code>Collection</code> of matching <code>AttributeValue</code>s or null if there are no
     *         matches
     */
    public function matchingValues(PipRequest $pipRequest, $listAttributeValues)
    {
        if ($listAttributeValues->size() === 0) {
            return $listAttributeValues;
        }

        /*
         * See if all of the values match the requested data type
         */
        $allMatch = true;
        foreach ($listAttributeValues as $attributeValue) {
            $allMatch = $attributeValue->getDataTypeId()->equals($pipRequest->getDataTypeId());
        }
        if ($allMatch) {
            return $listAttributeValues;
        }

        /*
         * If there was only one, return a null list
         */
        if ($listAttributeValues->size() == 1) {
            return null;
        }

        $listAttributeValuesMatching = new Set();
        foreach ($listAttributeValues as $attributeValue) {

            if ($attributeValue->getDataTypeId()->equals($pipRequest->getDataTypeId())) {
                $listAttributeValuesMatching->add($attributeValue);
            }
        }

        return $listAttributeValuesMatching->isEmpty() ? null : $listAttributeValuesMatching;
    }

    /**
     * Returns a {@link org.apache.openaz.xacml.api.pip.PipResponse} that only contains the
     * {@link org.apache.openaz.xacml.api.Attribute}s that match the given
     * {@link org.apache.openaz.xacml.api.pip.PipRequest} with
     * {@link org.apache.openaz.xacml.api.AttributeValue}s that match the requested data type.
     *
     * @param $pipRequest
     * @param $pipResponse
     *
     * @return
     * @throws org.apache.openaz.xacml.api.pip.PIPException
     */
    public function getMatchingResponse(PipRequest $pipRequest, PipResponse $pipResponse): PipResponse
    {
        /*
         * Error responses should not contain any $attributes, so just return the error response as is.
         * Likewise for empty responses
         */
        if (! $pipResponse->getStatus()->isOk() || $pipResponse->getAttributes()->size() == 0) {
            return $pipResponse;
        }

        /*
         * See if the response is simple
         */
        if ($pipResponse->isSimple()) {
            /*
             * Get the one Attribute and verify that it matches the request, and that the data type of its
             * values matches the request
             */
            $attributeResponse = $pipResponse->getAttributes()->next();
            if (matches($pipRequest, $attributeResponse)) {
                $attributeValues = $attributeResponse->getValues();
                if (! $attributeValues || $attributeValues->size() === 0) {
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
                    $listAttributeValuesMatch = $this->matchingValues($pipRequest,
                        $attributeResponse->getValues());
                    if ($listAttributeValuesMatch != null && $listAttributeValuesMatch->size() > 0) {
                        if ($attributeMatch == null) {
                            $attributeMatch = new Attribute($pipRequest->getCategory(),
                                $pipRequest->getAttributeId(),
                                $listAttributeValuesMatch,
                                $pipRequest->getIssuer(), false);
                        } else {
                            $attributeMatch->addValues($listAttributeValuesMatch);
                        }
                    }
                }
            }
            if ($attributeMatch == null) {
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
     * @param $listAttributes
     *
     * @return
     */
    protected function collapseAttributes($listAttributes)
    {
        if ($listAttributes->size() <= 0) {
            return $listAttributes;
        }
        $map = new Map();
        foreach ($listAttributes as $attribute) {
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
            $listAllAttributesSimple = new Set();
            foreach ($pipResponse->getAttributes() as $attribute) {
                $listAttributesSplit = $this->simplifyAttribute($attribute);
                if ($listAttributesSplit != null && $listAttributesSplit->size() > 0) {
                    $listAllAttributesSimple->addAll($listAttributesSplit);
                }
            }
            if ($listAllAttributesSimple->size() > 0) {
                $listAttributesCollapsed = $this->collapseAttributes($listAllAttributesSimple);
                foreach ($listAttributesCollapsed as $attributeCollapsed) {
                    $map->put(new PipRequest($attributeCollapsed), new PipResponse($attributeCollapsed));
                }
            }
        }

        return $map;
    }

    protected function generateOkResponse()
    {
        return new PipResponse(new Status(StatusCode::STATUS_CODE_OK()));
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