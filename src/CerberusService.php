<?php
declare(strict_types = 1);

namespace Cerberus;

use Cerberus\PDP\Utility\ArrayProperties;
use Cerberus\PEP\Action;
use Cerberus\PEP\PepAgent;
use Cerberus\PEP\PepAgentFactory;
use Cerberus\PEP\ResourceObject;
use Cerberus\PEP\Subject;
use Cerberus\PIP\Permission\PermissionManager;

class CerberusService
{
    /** @var PepAgent */
    protected $pepAgent;
    /** @var PermissionManager */
    protected $permissionManager;

    public function __construct(ArrayProperties $properties)
    {
        $this->pepAgent = (new PepAgentFactory($properties))->getPepAgent();
        $managerClass = $properties->get('contentSelector.classes.manager');
        $repositoryClass = $properties->get('contentSelector.classes.repository');
        $repoConfig = $properties->get('contentSelector.config.repository');
        $repository = new $repositoryClass($repoConfig);
        $this->permissionManager = new $managerClass($repository);
    }

    public function can(Subject $subject, Action $action, $resource, array $properties = []): bool
    {
        if (!empty($properties)) {
            $resource->addAttribute('resource-properties', $properties['properties']);
        }
        $response = $this->pepAgent->decide($subject, $action, $resource);

        return $response->allowed();
    }

    public function deny(Subject $subject, Action $action, $resource, array $properties = [])
    {
        if (!empty($properties)) {
            $resource->addAttribute('resource-properties', $properties['properties']);
        }
        $resourceObject = new ResourceObject(get_class($resource), (string) $resource->getId());
        $this->permissionManager->deny($subject, $action, $resourceObject);
    }

    public function grant(Subject $subject, Action $action, $resource, array $properties = [])
    {
        if (!empty($properties)) {
            $resource->addAttribute('resource-properties', $properties['properties']);
        }
        $resourceObject = new ResourceObject(get_class($resource), (string) $resource->getId());
        $this->permissionManager->grant($subject, $action, $resourceObject);
    }
}
