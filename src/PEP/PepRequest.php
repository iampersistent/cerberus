<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\MutableRequest;
use Cerberus\Core\Request;
use Ds\Map;

class PepRequest
{
    protected $mapperRegistry;
    protected $requestObjects;
    protected $pepConfig;
    protected $pepRequestAttributesMapByCategory;
    protected $wrappedRequest;

    public function __construct(MapperRegistry $mapperRegistry, $objects)
    {
        $this->mapperRegistry = $mapperRegistry;
        $this->requestObjects = $objects;
        //$this->pepConfig = $pepConfig;
        $this->pepRequestAttributesMapByCategory = new Map();
        //$this->idCounter = new AtomicInteger(1);
        $this->wrappedRequest = new MutableRequest();
        $this->map();

    }

    public function getPepRequestAttributes($categoryIdentifier): PepRequestAttributes
    {
        if ($this->pepRequestAttributesMapByCategory->hasKey($categoryIdentifier)) {
            return $this->pepRequestAttributesMapByCategory->get($categoryIdentifier);
        } else {
            $xmlId = uniqid('cerberus');
            $pepRequestAttributes = new PepRequestAttributes($xmlId, $categoryIdentifier);
            //$pepRequestAttributes->setIssuer($this->pepConfig->getIssuer());
            $this->pepRequestAttributesMapByCategory->put($categoryIdentifier, $pepRequestAttributes);
            $this->wrappedRequest->add($pepRequestAttributes->getWrappedRequestAttributes());
        }

        return $pepRequestAttributes;
    }

    public function getWrappedRequest(): Request
    {
        return $this->wrappedRequest;
    }

    private function map()
    {
        if ($this->requestObjects == null) {
            throw new IllegalArgumentException("One or more arguments are null");
        }
        foreach ($this->requestObjects as $object) {

            $mapper = $this->mapperRegistry->getMapper(get_class($object));
            if ($mapper == null) {
                throw new IllegalArgumentException("No mappers found for class: " + get_class($object));
            }
            $mapper->map($object, $this);
        }
    }
}