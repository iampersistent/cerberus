<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Enums\{
    AttributeIdentifier, ResourceIdentifier
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
        $pepRequestAttributes = $pepRequest->getPepRequestAttributes(AttributeIdentifier::RESOURCE_CATEGORY);
        foreach ($this->config as $name => $lookup) {
            if (ResourceIdentifier::RESOURCE_TYPE === $name) {
                $pepRequestAttributes->addAttribute($name, $lookup);

                continue;
            }
            $pepRequestAttributes->addAttribute($name, $object->$lookup());
        }
    }
}