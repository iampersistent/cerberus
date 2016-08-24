<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

abstract class ObjectMapper
{
    protected $className = '';
    /** @var MapperRegistry */
    protected $mapperRegistry;

    /**
     * Returns a Class that represents the mapped domain type.
     */
    public function getMappedClass()
    {
        return $this->className;
    }

    /**
     * Maps Object properties to attributes
     *
     * @param o - an instance of the domain object to be mapped
     * @param pepRequest - the current Request Context
     */
    public function map($object, PepRequest $pepRequest)
    {

    }

    /**
     * @param mapperRegistry
     */
    public function setMapperRegistry(MapperRegistry $mapperRegistry)
    {
        $this->mapperRegistry = $mapperRegistry;
    }

    /**
     * @param pepConfig
     */
    public function setPepConfig(PepConfig $pepConfig)
    {

    }
}