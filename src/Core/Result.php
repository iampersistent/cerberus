<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Collection;

class Result
{
    /**
     * Gets the {@link org.apache.openaz.xacml.api.Decision} associated with this <code>Result</code>.
     *
     * @return the <code>Decision</code> associated with this <code>Result</code>.
     */
    public function getDecision(): Decision
    {

    }

    /**
     * Gets the {@link org.apache.openaz.xacml.api.Status} associated with this <code>Result</code>.
     *
     * @return the <code>Status</code> associated with this <code>Result</code>
     */
    public function getStatus(): Status
    {

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

    }

    /**
     * Gets the <code>Collection</code> of {@link org.apache.openaz.xacml.api.IdReference} objects referring to XACML 3.0 PolicySets
     * that are in this <code>Result</code>.
     *
     * @return the <code>Collection</code> of PolicySet <code>IdReference</code>s in this <code>Result</code>.
     */
    public function getPolicySetIdentifiers(): Collection
    {

    }

    /**
     * {@inheritDoc} Implementations of this interface must override the <code>equals</code> method with the
     * following semantics: Two <code>Result</code>s (<code>r1</code> and <code>r2</code>) are equal if:
     * {@code r1.getDecision() == r2.getDecision()} AND {@code r1.getStatus().equals(r2.getStatus()} AND
     * {@code r1.getObligations()} is pair-wise equal to {@code r2.getObligations()}
     * {@code r1.getAssociatedAdvice()} is pair-wise equal to {@code r2.getAssociatedAdvice()}
     * {@code r1.getAttributes()} is pair-wise equal to {@code r2.getAttributes()}
     * {@code r1.getPolicyIdentifiers()} is pair-wise equal to {@code r2.getPolicyIdentifiers()}
     * {@code r1.getPolicySetIdentifiers()} is pair-wise equal to {@code r2.getPolicySetIdentifiers()}
     */
//@Override
//boolean equals(Object obj);
}