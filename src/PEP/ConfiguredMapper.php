<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\CategoryType;

class ConfiguredMapper extends ObjectMapper
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->className = $config['className'];
        unset($this->config['className']);
    }

    public function map($object, PepRequest $pepRequest)
    {
        $requestAttributes = $pepRequest->getPepRequestAttributes(CategoryType::ID_ATTRIBUTE_CATEGORY_RESOURCE);
        foreach ($this->config as $name => $lookup) {
            $requestAttributes->addAttribute($name, $object->$lookup);
        }
    }
}