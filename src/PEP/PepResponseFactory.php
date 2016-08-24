<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class PepResponseFactory
{
    protected $mapperRegistry;
    protected $pepConfig;

    public function __construct(MapperRegistry $mapperRegistry)
    {
        //$this->pepConfig = $pepConfig;
        $this->mapperRegistry = $mapperRegistry;
    }

    public function newPepRequest($objects): PepRequest
    {
        return new PepRequest($this->mapperRegistry, $objects);
    }

    public function newBulkPepRequest(array $associations, $objects): PepRequest
    {
        return new MultiRequest($this->pepConfig, $this->mapperRegistry, $associations, $objects);
    }
}