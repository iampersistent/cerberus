<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\PDP\Utility\NullProperties;
use Cerberus\PDP\Utility\Properties;
use Cerberus\PEP\Action\ActionMapper;
use Cerberus\PEP\Exception\PepException;
use Ds\Map;

class MapperRegistry
{
    protected $map;

    public function __construct(Properties $properties = null)
    {
        $properties = $properties ?? new NullProperties();

        $this->map = new Map();
        $this->registerMappers([
            new ActionMapper(),
            new CategoryContainerMapper(),
            new ResourceObjectMapper(),
            new SubjectMapper(),
        ]);

        $mapperClasses = $properties->get('pep.mappers.classes', []);
        foreach ($mapperClasses as $mapperClass) {
            $this->registerMapper(new $mapperClass());
        }

        $mapperConfigs = $properties->get('pep.mappers.configurations', []);
        foreach ($mapperConfigs as $configFile) {
            // config files must return a php array
            $this->registerMapper(new ConfiguredMapper(require $configFile));
        }
        if ($contentSelectorMapper = $properties->get('contentSelector.classes.mapper')) {
            $repositoryClass = $properties->get('contentSelector.classes.repository');
            $repoConfig = $properties->get('contentSelector.config.repository');
            $repository = new $repositoryClass($repoConfig);
            $this->registerMapper(new $contentSelectorMapper($repository));
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

    public function hasMapper($className): bool
    {
        return (bool) $mapper = $this->getClassMapper($className);
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