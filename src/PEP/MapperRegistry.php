<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\PEP\Exception\PepException;
use Ds\Map;

class MapperRegistry
{
    protected $map;

    public function __construct($config = [])
    {
        $this->map = new Map();
        $this->registerMappers([
            new ActionMapper(),
            new CategoryContainerMapper(),
            new ResourceMapper(),
            new SubjectMapper(),
        ]);
        foreach ($config as $classConfig) {
            $this->registerMapper(new ConfiguredMapper($classConfig));
        }
    }

    public function getMapper($className): ObjectMapper
    {
        if ($mapper = $this->getClassMapper($className)) {
            return $mapper;
        } else {
            throw new PepException("No ObjectMapper found for Object of Class: $className");
        }
    }

    public function registerMapper(ObjectMapper $mapper)
    {
        $mapper->setMapperRegistry($this);
        $this->map->put($mapper->getMappedClass(), $mapper);
    }

    public function registerMappers(array $mappers)
    {
        foreach ($mappers as $mapper) {
            $this->registerMapper($mapper);
        }
    }

    protected function getClassMapper($className)
    {
        /** @var ObjectMapper */
        $mapper = $this->map->get($className, null);
        if (! $mapper) {
            foreach (class_parents($className) as $class) {
                $mapper = $this->map->get($class, null);
                if ($mapper) {
                    return $mapper;
                }
            }
        }

        return $mapper;
    }
}