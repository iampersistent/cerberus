<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use UnitTester;
use Cerberus\PEP\{
    Action\ReadAction, PepAgent, ResourceObject, Subject
};
use Cerberus\PIP\Contract\PermissionRepository;

class ContentSelectorMappingCest extends MatchBaseCest
{
    protected $policyPath = 'dynamic-policy';
    /** @var PepAgent */
    protected $pepAgent;
    /** @var PermissionRepository */
    protected $repository;

    public function _before(UnitTester $I)
    {
        parent::_before($I);

        $properties = $this->pepAgent->getPepConfig()->properties;

        $repositoryClass = $properties->get('contentSelector.classes.repository');
        $repoConfig = $properties->get('contentSelector.config.repository');
        $this->repository = new $repositoryClass($repoConfig);
    }

    public function testDeny(UnitTester $I)
    {
        $subject = new Subject('subjectIdJSmith');
        $resource = new ResourceObject('fileResolver', 'fileId12345');
        $response = $this->pepAgent->decide($subject, new ReadAction(), $resource);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function testPermit(UnitTester $I)
    {
        $subject = new Subject('subjectIdJSmith');
        $resource = new ResourceObject('fileResolver', 'fileId12345');

        $this->addRecord($resource, $subject, ['read', 'write']);

        $response = $this->pepAgent->decide($subject, new ReadAction(), $resource);
        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }
}