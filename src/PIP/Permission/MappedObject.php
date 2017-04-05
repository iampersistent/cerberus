<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Permission;

use Cerberus\Core\Enums\{
    ActionIdentifier, ResourceIdentifier, SubjectIdentifier
};
use Cerberus\PEP\Action\Action;
use Cerberus\PEP\ResourceObject;
use Cerberus\PEP\Subject;

class MappedObject
{
    protected $id;
    protected $subjectId;
    protected $subjectType;
    protected $resourceId;
    protected $resourceType;
    protected $actions = [];

    public function __construct(array $options = [])
    {
        $this->fill($options);
    }

    public function fill(array $options)
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        if (! is_array($this->actions)) {
            $this->actions = json_decode($this->actions);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSubjectId(): string
    {
        return (string)$this->subjectId;
    }

    public function getSubjectType(): string
    {
        return $this->subjectType;
    }

    public function getResourceId(): string
    {
        return (string)$this->resourceId;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function addAction(Action $action): self
    {
        $actionId = $action->getAttribute(ActionIdentifier::ACTION_ID);

        if (false === $key = array_search($actionId, $this->actions)) {
            $this->actions[] = $actionId;
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        $actionId = $action->getAttribute(ActionIdentifier::ACTION_ID);

        if (false !== $key = array_search($actionId, $this->actions)) {
            unset($this->actions[$key]);
        }

        return $this;
    }

    public function hasAction(Action $action): bool
    {
        $actionId = $action->getAttribute(ActionIdentifier::ACTION_ID);

        return in_array($actionId, $this->actions);
    }

    public function getIdentifiers(): array
    {
        return [
            'subjectId'    => $this->subjectId,
            'subjectType'  => $this->subjectType,
            'resourceId'   => $this->resourceId,
            'resourceType' => $this->resourceType,
        ];
    }

    public function toPathArray(): array
    {
        return [
            'resource' => [
                'id'   => $this->resourceId,
                'type' => $this->resourceType,
            ],
            'subject'  => [
                'id'   => $this->subjectId,
                'type' => $this->subjectType,
            ],
            'actions'  => $this->actions,
        ];
    }

    public static function fromSubjectResource(Subject $subject, ResourceObject $resource): self
    {
        return new static([
            'subjectId'    => $subject->getAttribute(SubjectIdentifier::SUBJECT_ID),
            'subjectType'  => $subject->getAttribute(SubjectIdentifier::SUBJECT_TYPE),
            'resourceId'   => $resource->getAttribute(ResourceIdentifier::RESOURCE_ID),
            'resourceType' => $resource->getAttribute(ResourceIdentifier::RESOURCE_TYPE),
        ]);
    }
}