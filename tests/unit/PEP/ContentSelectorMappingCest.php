<?php
declare(strict_types = 1);

use Cerberus\PDP\{
    Utility\ArrayProperties
};
use Cerberus\PEP\{
    Action\Action, PepAgent, PepAgentFactory, ResourceObject, Subject
};
use Cerberus\PIP\Contract\PermissionRepository;
use Cerberus\PIP\Permission\MappedObject;

class ContentSelectorMappingCest
{
    /** @var PepAgent */
    protected $pepAgent;
    /** @var PermissionRepository */
    protected $repository;

    public function _before(UnitTester $I)
    {
        $properties = require __DIR__ . '/../../_data/fixtures/PEP/testContentSelectorMapperProperties.php';
        $properties = new ArrayProperties($properties);
        $this->pepAgent = (new PepAgentFactory($properties))->getPepAgent();
        $repositoryClass = $properties->get('contentSelector.classes.repository');
        $repoConfig = $properties->get('contentSelector.config.repository');
        $this->repository = new $repositoryClass($repoConfig);
    }

    public function testDeny(UnitTester $I)
    {
        $subject = new Subject('subjectIdJSmith');
        $action = new Action('read');
        $resource = new ResourceObject('fileResolver', 'fileId12345');
        $response = $this->pepAgent->decide($subject, $action, $resource);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function testPermit(UnitTester $I)
    {
        // grant permission
        $record = new MappedObject([
            'resourceId'   => 'fileId12345',
            'resourceType' => 'fileResolver',
            'subjectType'  => 'user',
            'subjectId'    => 'subjectIdJSmith',
            'actions'      => ['read', 'write'],
        ]);
        $this->repository->save($record);
        $subject = new Subject('subjectIdJSmith');
        $action = new Action('read');
        $resource = new ResourceObject('fileResolver', 'fileId12345');

        $response = $this->pepAgent->decide($subject, $action, $resource);
        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }
}