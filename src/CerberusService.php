<?php
declare(strict_types = 1);

namespace Cerberus;

use Cerberus\PDP\Utility\ArrayProperties;
use Cerberus\PEP\Action;
use Cerberus\PEP\PepAgentFactory;
use Cerberus\PEP\ResourceObject;
use Cerberus\PEP\Subject;

class CerberusService
{
    protected $pepAgent;

    public function __construct(ArrayProperties $properties)
    {
        $this->pepAgent = (new PepAgentFactory($properties))->getPepAgent();
    }

    public function can(array $user, $action, array $resource, array $properties = []): bool
    {
        $subject = new Subject($user['id']);
        unset($user['id']);
        foreach ($user as $id => $value) {
            $subject->addAttribute($id, $value);
        }

        $action = new Action($action);

        $resource = new ResourceObject((string) $resource['type'], (string) $resource['id']);
        if (!empty($properties)) {
            $resource->addAttribute('resource-properties', $properties['properties']);
        }
        $response = $this->pepAgent->decide($subject, $action, $resource);

        return $response->allowed();
    }

    public function deny($user, $action, $resource, $properties = [])
    {

    }

    public function grant($user, $action, $resource, $properties = [])
    {

    }
}
