<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Set;

class Result
{
    protected $decision;
    protected $obligations;
    protected $policyIdentifiers;
    protected $policySetIdentifiers;
    protected $status;

    public function __construct()
    {
        $this->obligations = new Set();
    }

    public function addPolicyIdentifiers($policyIdentifiers)
    {

    }

    public function addPolicySetIdentifiers($policySetIdentifiers)
    {

    }

    public function addAttributeCategories($attributeCategories)
    {

    }

    public function getDecision(): Decision
    {
        return $this->decision;
    }

    public function setDecision(Decision $decision)
    {
        $this->decision = $decision;
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

    public function setStatus(Status $status)
    {
        $this->status = $status;
    }
}