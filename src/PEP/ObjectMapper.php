<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

abstract class ObjectMapper
{
    /**
     * Returns a Class that represents the mapped domain type.
     *
     * @return a Class name
     */
    public function getMappedClass()
    {

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

    }

    /**
     * @param pepConfig
     */
    public function setPepConfig(PepConfig $pepConfig)
    {

    }
}