<?php
declare(strict_types = 1);

namespace Cerberus;

use Cerberus\PDP\{ArrayPolicyFinderFactory, CerberusEngineFactory};
use Cerberus\PDP\Policy\{CombiningAlgorithmFactory, Factory\FunctionDefinitionFactory};
use Cerberus\PDP\Utility\ArrayProperties;
use Cerberus\PEP\Action\Action;
use Cerberus\PEP\{PepAgent, PepAgentFactory, PersistedResourceMapper, ResourceObject, Subject};
use Cerberus\PIP\Factory\PipFinderFactory;
use Cerberus\PIP\Permission\{PermissionManager, PermissionMemoryRepository};

class CerberusService
{
    /** @var PepAgent */
    protected $pepAgent;
    /** @var PermissionManager */
    protected $permissionManager;

    protected $defaults = [
        'factories'       => [
            'combiningAlgorithm' => CombiningAlgorithmFactory::class,
            'functionDefinition' => FunctionDefinitionFactory::class,
            'pdpEngine'          => CerberusEngineFactory::class,
            'pipFinder'          => PipFinderFactory::class,
            'policyFinder'       => ArrayPolicyFinderFactory::class,
        ],
        'rootPolicies'    => [],
        'pep'             => [
            'issuer'  => 'test',
            'mappers' => [
                'classes'        => [],
                'configurations' => [],
            ],
        ],
        'contentSelector' => [
            'classes' => [
                'mapper'     => PersistedResourceMapper::class,
                'manager'    => PermissionManager::class,
                'repository' => PermissionMemoryRepository::class,
            ],
            'config'  => [
                'repository' => [],
            ],
        ],
    ];

    public function __construct(ArrayProperties $properties)
    {
        $properties = (new ArrayProperties($this->defaults))->merge($properties);

        $this->pepAgent = (new PepAgentFactory($properties))->getPepAgent();
        $managerClass = $properties->get('contentSelector.classes.manager');
        $repositoryClass = $properties->get('contentSelector.classes.repository');
        $repoConfig = $properties->get('contentSelector.config.repository');
        $repository = new $repositoryClass($repoConfig);
        $this->permissionManager = new $managerClass($repository);
    }

    public function can(Subject $subject, Action $action, $resource, array $properties = []): bool
    {
        if (! empty($properties)) {
            $resource = $this->getResourceObject($resource);
            $resource->addAttribute('resource-properties', $properties['properties']);
        }
        $response = $this->pepAgent->decide($subject, $action, $resource);

        return $response->allowed();
    }

    public function deny(Subject $subject, Action $action, $resource, array $properties = [])
    {
        $resource = $this->getResourceObject($resource);
        if (! empty($properties)) {
            $resource->addAttribute('resource-properties', $properties['properties']);
        }
        $this->permissionManager->deny($subject, $action, $resource);
    }

    public function grant(Subject $subject, Action $action, $resource, array $properties = [])
    {
        $resource = $this->getResourceObject($resource);
        if (! empty($properties)) {
            $resource->addAttribute('resource-properties', $properties['properties']);
        }
        $this->permissionManager->grant($subject, $action, $resource);
    }

    public function find(Subject $subject, $resource)
    {
        return $this->permissionManager->find($subject, $this->getResourceObject($resource));
    }

    protected function getResourceObject($resource): ResourceObject
    {
        return $resource instanceof ResourceObject
            ? $resource
            : new ResourceObject(get_class($resource), (string)$resource->getId());
    }
}
