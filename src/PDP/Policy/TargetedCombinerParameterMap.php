<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\PDP\Exception\IllegalStateException;
use Ds\Map;
use Ds\Set;

class TargetedCombinerParameterMap
{
    /** @var Map */
    protected $mapTargetToCombinerParameters;
    protected $mapTargetIdToTarget;
    protected $targetedCombinerParameters;

    public function __construct()
    {
        $this->targetedCombinerParameters = new Set();
        $this->mapTargetIdToTarget = new Map();
    }

    public function addCombinerParameter(TargetedCombinerParameter $combinerParameter)
    {
        $this->targetedCombinerParameters->add($combinerParameter);
        $this->mapTargetToCombinerParameters = null;
    }

    public function getCombinerParameters($target)
    {
        $this->ensureMap();

        return !$this->mapTargetToCombinerParameters ? null : $this->mapTargetToCombinerParameters->get($target);
    }

    protected function ensureMap()
    {
        if ($this->mapTargetToCombinerParameters == null && $this->targetedCombinerParameters->count() > 0) {
            $this->mapTargetToCombinerParameters = new Map();
            foreach ($this->targetedCombinerParameters as $targetedCombinerParameter) {
                if (! $target = $this->resolve($targetedCombinerParameter)) {
                    throw new IllegalStateException("Unresolved TargetCombinerParameter \"" . $targetedCombinerParameter->toString() . "\"");
                }
                if (! $listCombinerParameters = $this->mapTargetToCombinerParameters->get($target)) {
                    $listCombinerParameters = new Set();
                    $this->mapTargetToCombinerParameters->put($target, $listCombinerParameters);
                }
                $listCombinerParameters->add($targetedCombinerParameter);
            }
        }
    }

    protected function resolve(TargetedCombinerParameter $targetedCombinerParameter)
    {
        if ($result = $targetedCombinerParameter->getTarget()) {
            return $result;
        } else {
            if ($result = $this->mapTargetIdToTarget->get($targetedCombinerParameter->getTargetId())) {
                $targetedCombinerParameter->setTarget($result);

                return $result;
            }
        }

        return null;
    }
}