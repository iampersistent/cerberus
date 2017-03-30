<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Enums\{
    AttributeCategoryIdentifier, ResourceIdentifier
};

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
        $pepRequestAttributes = $pepRequest->getPepRequestAttributes(AttributeCategoryIdentifier::RESOURCE);
        foreach ($this->config as $name => $lookup) {
            if (ResourceIdentifier::RESOURCE_TYPE === $name) {
                $pepRequestAttributes->addAttribute($name, $lookup);

                continue;
            }
            $pepRequestAttributes->addAttribute($name, $object->$lookup());
        }
    }
}