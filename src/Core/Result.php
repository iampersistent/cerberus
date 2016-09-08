<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Set;

class Result
{
    /** @var Advice[]|Set */
    protected $associatedAdvice;

    /** @var AttributeCategory[]|Set */
    protected $attributes;
    protected $decision;
    protected $obligations;

    /** @var mixed[]|Set */
    protected $policyIdentifiers;
    /** @var mixed[]|Set */
    protected $policySetIdentifiers;
    protected $status;

    public function __construct()
    {
        $this->associatedAdvice = new Set();
        $this->attributes = new Set();
        $this->obligations = new Set();
        $this->policyIdentifiers = new Set();
        $this->policySetIdentifiers = new Set();
    }

    public function addAdvice(...$advice): self
    {
        $this->associatedAdvice->add($advice);

        return $this;
    }

    public function addAttributeCategories(...$attributeCategories): self
    {
        $this->attributes->add($attributeCategories);

        return $this;
    }

    public function addPolicyIdentifier($policyIdentifier): self
    {
        $this->policyIdentifiers->add($policyIdentifier);

        return $this;
    }

    public function addPolicyIdentifiers(...$policyIdentifiers): self
    {
        $this->policyIdentifiers->add($policyIdentifiers);

        return $this;
    }

    public function addPolicySetIdentifiers(...$policySetIdentifiers): self
    {
        $this->policySetIdentifiers->add($policySetIdentifiers);

        return $this;
    }

    public function getDecision(): Decision
    {
        return $this->decision;
    }

    public function setDecision(Decision $decision): self
    {
        $this->decision = $decision;

        return $this;
    }


    public function addObligation(Obligation $obligation): self
    {
        $this->obligations->add($obligation);

        return $this;
    }


    public function addObligations($obligations): self
    {
        $this->obligations->add($obligations);

        return $this;
    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.Obligation}s int this
     * <code>Result</code>. If there are no <code>Obligation</code>s this method must return an empty
     * <code>Collection</code>.
     *
     * @return the <code>Collection</code> of {@link org.apache.openaz.xacml.api.Obligation}s Obligation
     *         <code>Result</code>.
     */
    public function getObligations(): Set
    {
        return $this->obligations;
    }

    /**
     * Gets the <code>Collection</code> of {@link Advice} objects in this <code>Result</code>. If there are no
     * <code>Advice</code> codes this method must return an empty <code>Collection</code>.
     *
     * @return the <code>Collection</code> of <code>Advice</code> objects in this <code>Result</code>.
     */
    public function getAssociatedAdvice(): Set
    {

    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.AttributeCategory} objects in this <code>Result</code>.  If there
     * are no <code>AttributeCategory</code> objects this method must return an empty <code>Collection</code>.
     *
     * @return the <code>Collection</code> of <code>AttributeCategory</code> objects in this
     *         <code>Result</code>.
     */
    public function getAttributes(): Set
    {

    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.IdReference} objects referring to XACML 3.0 Policies
     * that are in this <code>Result</code>.
     *
     * @return the <code>Collection</code> of Policy <code>IdReference</code>s in this <code>Result</code>.
     */
    public function getPolicyIdentifiers(): Set
    {
        return $this->policyIdentifiers;
    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.IdReference} objects referring to XACML 3.0 PolicySets
     * that are in this <code>Result</code>.
     *
     * @return the <code>Collection</code> of PolicySet <code>IdReference</code>s in this <code>Result</code>.
     */
    public function getPolicySetIdentifiers(): Set
    {
        return $this->policySetIdentifiers;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;

        return $this;
    }
}