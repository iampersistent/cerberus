<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Map;
use Ds\Set;

class ObligationRouter
{
    /** @var ObligationHandlerRegistry */
    protected $registrationHandler;

    /** @var ThreadLocalObligationStore */
    protected $obligationStore;

    public function __construct()
    {
    }

    public function routeObligations(Map $obligationMap)
    {
        // Clear any stale Obligations on the current thread.
        $this->obligationStore->clear();
        if ($obligationMap != null) {
            $obligationMapByHandlerClass = new Map();
            foreach ($obligationMap->pairs() as $pair) {
                $isObligationHandleable = false;
                $obligationId = $pair->key;
                $obligation = $pair->value; // Obligation
                foreach ($this->registrationHandler->getRegisteredHandlerMap()->pairs() as $mapPair) {
                    $handlerClass = $mapPair->key;
                    $matchable = $mapPair->value; // Match Obligation
                    if ($matchable->match($obligation)) {
                        $handlerObligationSet = $obligationMapByHandlerClass->get($handlerClass);
                        if ($handlerObligationSet == null) {
                            $handlerObligationSet = new Set();
                            $obligationMapByHandlerClass->put($handlerClass, $handlerObligationSet);
                        }
                        $handlerObligationSet->add($obligation);
                        $isObligationHandleable = true;
//if (logger.isDebugEnabled()) {
//    logger.debug("Obligation - " + $obligationId + " matched by Handler - "
//        + $handlerClass);
//}
                    }
                }
                if (! $isObligationHandleable) {
                    throw new UnhandleableObligationException(
                        "No ObligationHandlers available for handling Obligation: "
                        . $pair->getKey());
                }
            }
            $this->obligationStore->setObligations($obligationMapByHandlerClass);
        }
    }
}