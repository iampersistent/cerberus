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

    public function getObligations(): Set
    {
        return $this->obligations;
    }

    public function getAssociatedAdvice(): Set
    {
        return $this->associatedAdvice;
    }

    public function getAttributes(): Set
    {
        return $this->attributes;
    }

    public function getPolicyIdentifiers(): Set
    {
        return $this->policyIdentifiers;
    }

    public function getPolicySetIdentifiers(): Set
    {
        return $this->policySetIdentifiers;
    }

    /**
     * @return Status|null
     */
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