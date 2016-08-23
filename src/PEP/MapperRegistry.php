<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class MapperRegistry
{
    protected $map;

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
}