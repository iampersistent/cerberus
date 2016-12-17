<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Exception\IllegalArgumentException;
use Cerberus\Core\Request;
use Ds\Map;

class PepRequest extends Request
{
    protected $mapperRegistry;
    protected $requestObjects;
    protected $pepConfig;
    protected $pepRequestAttributesMapByCategory;

    public function __construct(MapperRegistry $mapperRegistry, $objects)
    {
        parent::__construct();
        if (! is_array($objects)) {
            $objects = [$objects];
        }
        $this->mapperRegistry = $mapperRegistry;
        $this->requestObjects = $objects;
        //$this->pepConfig = $pepConfig;
        $this->pepRequestAttributesMapByCategory = new Map();
        $this->map();
    }

    public function getPepRequestAttributes($categoryIdentifier): PepRequestAttributes
    {
        if ($this->pepRequestAttributesMapByCategory->hasKey($categoryIdentifier)) {
            return $this->pepRequestAttributesMapByCategory->get($categoryIdentifier);
        }
        $id = uniqid('cerberus');
        $pepRequestAttributes = new PepRequestAttributes($id, $categoryIdentifier);
        //$pepRequestAttributes->setIssuer($this->pepConfig->getIssuer());
        $this->pepRequestAttributesMapByCategory->put($categoryIdentifier, $pepRequestAttributes);
        $this->add($pepRequestAttributes);

        return $pepRequestAttributes;
    }

    protected function map()
    {
        if (! $this->requestObjects) {
            throw new IllegalArgumentException('One or more arguments are null');
        }
        foreach ($this->requestObjects as $object) {
            $mapper = $this->mapperRegistry->getMapper(get_class($object));
            if (! $mapper) {
                throw new IllegalArgumentException('No mappers found for class: ' . get_class($object));
            }
            $mapper->map($object, $this);
        }
    }
}