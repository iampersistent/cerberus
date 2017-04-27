<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use Cerberus\Core\Enums\ResourceIdentifier;
use Cerberus\Core\Enums\SubjectIdentifier;
use Cerberus\PDP\Utility\ArrayProperties;
use Cerberus\PEP\{
    PepAgent, PepAgentFactory, ResourceObject, Subject
};
use Cerberus\PIP\Permission\MappedObject;
use Cerberus\PIP\Permission\PermissionMemoryRepository;
use TestData\Document;
use UnitTester;

class MatchBaseCest
{
    /** @var string */
    protected $userId = '5';
    /** @var string */
    protected $policyPath;
    /** @var string[] */
    protected $configurationMappers = [
        'documentMapper',
    ];

    /** @var PepAgent */
    protected $pepAgent;
    /** @var PermissionMemoryRepository */
    protected $repository;

    /** @var Subject */
    protected $subject;

    /** @var Document */
    protected $document;
    /** @var Document */
    protected $alternateDocument;

    /** @var  ArrayProperties */
    private $properties;

    public function _before(UnitTester $I)
    {
        $properties = $this->properties ?: $this->properties = $this->getProperties();

        $this->pepAgent = (new PepAgentFactory($properties))->getPepAgent();
        $repositoryClass = $properties->get('contentSelector.classes.repository');
        $repoConfig = $properties->get('contentSelector.config.repository');
        $this->repository = new $repositoryClass($repoConfig);

        $this->subject = new Subject($this->userId);
        $this->document = new Document(1, "OnBoarding Document", "ABC Corporation", $this->userId);
        $this->alternateDocument = new Document(42, "OnBoarding Document", "XYZ Corporation", "Jim Doe");
    }

    protected function getProperties(): ArrayProperties
    {
        $defaultsPath = codecept_data_dir('fixtures/PEP/defaultProperties.php');
        $defaults = require $defaultsPath;

        foreach($this->configurationMappers ?: [] as $mapper) {
            if (class_exists($mapper)) {
                $defaults['pep']['mappers']['classes'][] = $mapper;
            } else {
                if (! pathinfo($mapper, PATHINFO_EXTENSION)) {
                    $mapper .= '.php';
                }

                $defaults['pep']['mappers']['configurations'][] = codecept_data_dir('fixtures/PEP/Mappers/' . $mapper);
            }
        }

        if (! pathinfo($this->policyPath, PATHINFO_EXTENSION)) {
            $this->policyPath .= '.php';
        }
        $defaults['rootPolicies'][] = codecept_data_dir('fixtures/PEP/Policies/' . $this->policyPath);

        return new ArrayProperties($defaults);
    }

    protected function addRecord(ResourceObject $resource, Subject $subject, $actions = 'write')
    {
        if (is_string($actions)) {
            $actions = [$actions];
        }

        // grant permission
        $record = new MappedObject([
            'resourceId'   => $resource->getAttribute(ResourceIdentifier::RESOURCE_ID),
            'resourceType' => $resource->getAttribute(ResourceIdentifier::RESOURCE_TYPE),
            'subjectId'    => $subject->getAttribute(SubjectIdentifier::SUBJECT_ID),
            'subjectType'  => $subject->getAttribute(SubjectIdentifier::SUBJECT_TYPE),
            'actions'      => $actions,
        ]);
        $this->repository->save($record);

        return $record;
    }
}