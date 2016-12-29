<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class PepRequestFactory
{
    protected $pepConfig;

    protected $mapperRegistry;

    public function __construct(MapperRegistry $mapperRegistry) // PepConfig $pepConfig,
    {
        //$this->pepConfig = $pepConfig;
        $this->mapperRegistry = $mapperRegistry;
    }

    public function newPepRequest(...$objects): PepRequest
    {
        return new PepRequest($this->mapperRegistry, $objects);
    }

//    public function newBulkPepRequest(List $associations, Object[] objects): PepRequest {
//return MultiRequest.newInstance(pepConfig, mapperRegistry, associations, objects);
//}
}