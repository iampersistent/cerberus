<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\PEP\Exception\PepException;
use Ds\Map;

class MapperRegistry
{
    protected $map;

    public function __construct()
    {
        $this->map = new Map();
        $this->registerMappers([
           new ActionMapper(),
           new CategoryContainerMapper(),
           new ResourceMapper(),
           new SubjectMapper(),
        ]);
    }

    public function getMapper($className): ObjectMapper
    {
        $mapper = $this->getClassMapper($className);

// Handle Arrays.
//if (mapper == null && clazz . isArray()) {
//    mapper = getMapper(Object[] .class);
//        }

        if ($mapper) {
            return $mapper;
        } else {
            throw new PepException("No ObjectMapper found for Object of Class: " + $className);
        }
    }

    public function registerMapper(ObjectMapper $mapper)
    {
        //$mapper->setPepConfig($this->pepConfig);
        $mapper->setMapperRegistry($this);
        $this->map->put($mapper->getMappedClass(), $mapper);
    }

    public function registerMappers(array $mappers)
    {
        foreach ($mappers as $mapper) {
            $this->registerMapper($mapper);
        }
    }

    protected function getClassMapper($className): ObjectMapper
    {
        /** @var ObjectMapper */
        $mapper = $this->map->hasKey($className) ? $this->map->get($className) : null;
        if (! $mapper) {
            foreach (class_parents($className) as $class) {
                $mapper = $this->map->hasKey($class) ? $this->map->get($class) : null;
                if ($mapper) {
                    return $mapper;
                }
            }
        }

        return $mapper;
    }
}