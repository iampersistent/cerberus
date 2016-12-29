<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Map;
use Ds\Sequence;
use Ds\Set;

class Request
{
    /** @var bool */
    protected $combinedDecision = false;
    /** @var Set */
    protected $multiRequests;
    protected $requestAttributes;
    protected $requestAttributesByCategoryId;
    protected $requestAttributesById;
    protected $requestDefaults;
    protected $returnPolicyIdList;
    protected $status;

    public function __construct()
    {
        $this->requestAttributes = new Set();
        $this->requestAttributesByCategoryId = new Map();
        $this->requestAttributesById = new Map();
        $this->returnPolicyIdList = false;
    }

    public function add(RequestAttributes $newRequestAttributes)
    {
        // where is the class name?
        $this->requestAttributes->add($newRequestAttributes);
        if ($this->requestAttributesByCategoryId->hasKey($newRequestAttributes->getCategory())) {
            $requestAttributesForCategoryId = $this->requestAttributesByCategoryId->get($newRequestAttributes->getCategory());
        } else {
            $requestAttributesForCategoryId = new Set();
            $this->requestAttributesByCategoryId->put($newRequestAttributes->getCategory(),
                $requestAttributesForCategoryId);
        }
        $requestAttributesForCategoryId->add($newRequestAttributes);
        if ($newRequestAttributes->getId()) {
            $this->requestAttributesById->put($newRequestAttributes->getId(), $newRequestAttributes);
        }
        $attributeCategoryIncludeInResult = null; // AttributeCategory
        foreach ($newRequestAttributes->getAttributes() as $attribute) { // Attribute
            if ($attribute->getIncludeInResults()) {
                if ($attributeCategoryIncludeInResult) {
                    $attributeCategoryIncludeInResult = new AttributeCategory();
                    $attributeCategoryIncludeInResult->setCategory($newRequestAttributes->getCategory());
                }
                $attributeCategoryIncludeInResult->add($attribute);
            }
        }
        if ($attributeCategoryIncludeInResult) {
            if ($this->requestAttributesIncludeInResult === EMPTY_ATTRIBUTE_CATEGORY_LIST) {
                $this->requestAttributesIncludeInResult = new Map();
            }
            $this->requestAttributesIncludeInResult->add($attributeCategoryIncludeInResult);
        }
    }

    public function getRequestDefaults(): RequestDefaults
    {
        return $this->requestDefaults;
    }

    public function setReturnPolicyIdList(bool $state): self
    {
        $this->returnPolicyIdList = $state;

        return $this;
    }

    public function shouldReturnPolicyIdList(): bool
    {
        return $this->returnPolicyIdList;
    }

    public function getCombinedDecision(): bool
    {
        return $this->combinedDecision;
    }

    public function getRequestAttributeByCategoryId(string $category)
    {
        return $this->requestAttributesByCategoryId->get($category, null);
    }

    /**
     * @param string|null $category
     *
     * @return RequestAttributes[]|Set
     */
    public function getRequestAttributes(string $category = null): Set
    {
        if ($category) {
            if (!$attribute = $this->getRequestAttributeByCategoryId($category)) {
                return new Set();
            }

            return $attribute;
        }

        return $this->requestAttributes;
    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.RequestAttributes} representing
     * XACML Attributes elements for this <code>Request</code> that contain
     * {@link org.apache.openaz.xacml.api.Attribute}s where <code>getIncludeInResults</code> is true.
     *
     * @return a <code>Collection</code> of <code>RequestAttributes</code> containing one or more
     *         <code>Attribute</code>s to include in results.
     */
    public function getRequestAttributesIncludedInResult()
    {

    }


    public function getRequestAttributesById(string $id): RequestAttributes
    {
        return $this->requestAttributesById->get($id);
    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.RequestReference}s representing
     * XACML MultiRequest elements in this <code>Request</code>.
     *
     * @return Sequence|null
     */
    public function getMultiRequests()
    {
        // todo
        return null;
    }

    /**
     * @return Status|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritDoc} Implementations of the <code>Request</code> interface must override the
     * <code>equals</code> method with the following semantics: Two <code>Requests</code> (<code>r1</code> and
     * <code>r2</code>) are equals if:
     * {@code r1.getRequestDefaults() == null && r2.getRequestDefaults() == null} OR
     * {@code r1.getRequestDefaults().equals(r2.getRequestDefaults())} AND
     * {@code r1.getReturnPolicyIdList() == r2.getReturnPolicyIdList()} AND
     * {@code r1.getCombinedDecision() == r2.getCombinedDecision()} AND {@code r1.getRequestAttributes()} is
     * pairwise equal to {@code r2.getRequestAttributes()} AND {@code r1.getMultiRequests()} is pairwise equal
     * to {@code r2.getMultiRequests()}
     */
    public function equals($object): bool
    {
        // todo

        return false;
    }
}
