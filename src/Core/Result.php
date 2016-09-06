<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Collection;

class Result
{
    protected $decision;
    protected $obligations;
    protected $policyIdentifiers;
    protected $policySetIdentifiers;
    protected $status;

    /**
     * Gets the {@link org.apache.openaz.xacml.api.Decision} associated with this <code>Result</code>.
     *
     * @return the <code>Decision</code> associated with this <code>Result</code>.
     */
    public function getDecision(): Decision
    {
        return $this->decision;
    }

    /**
     * Gets the {@link org.apache.openaz.xacml.api.Status} associated with this <code>Result</code>.
     *
     * @return the <code>Status</code> associated with this <code>Result</code>
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.Obligation}s int this
     * <code>Result</code>. If there are no <code>Obligation</code>s this method must return an empty
     * <code>Collection</code>.
     *
     * @return the <code>Collection</code> of {@link org.apache.openaz.xacml.api.Obligation}s Obligation
     *         <code>Result</code>.
     */
    public function getObligations(): Collection
    {
        return $this->obligations;
    }

    /**
     * Gets the <code>Collection</code> of {@link Advice} objects in this <code>Result</code>. If there are no
     * <code>Advice</code> codes this method must return an empty <code>Collection</code>.
     *
     * @return the <code>Collection</code> of <code>Advice</code> objects in this <code>Result</code>.
     */
    public function getAssociatedAdvice(): Collection
    {

    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.AttributeCategory} objects in this <code>Result</code>.  If there
     * are no <code>AttributeCategory</code> objects this method must return an empty <code>Collection</code>.
     *
     * @return the <code>Collection</code> of <code>AttributeCategory</code> objects in this
     *         <code>Result</code>.
     */
    public function getAttributes(): Collection
    {

    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.IdReference} objects referring to XACML 3.0 Policies
     * that are in this <code>Result</code>.
     *
     * @return the <code>Collection</code> of Policy <code>IdReference</code>s in this <code>Result</code>.
     */
    public function getPolicyIdentifiers(): Collection
    {
        return $this->policyIdentifiers;
    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.IdReference} objects referring to XACML 3.0 PolicySets
     * that are in this <code>Result</code>.
     *
     * @return the <code>Collection</code> of PolicySet <code>IdReference</code>s in this <code>Result</code>.
     */
    public function getPolicySetIdentifiers(): Collection
    {
        return $this->policySetIdentifiers;
    }
}