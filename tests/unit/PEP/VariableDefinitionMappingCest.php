<?php
declare(strict_types = 1);

use AspectMock\Test as Mock;
use Cerberus\Core\Identifier;
use Cerberus\PEP\{
    Action, MapperRegistry, ObjectMapper, PepAgent, PepAgentFactory, PepRequest, PepRequestFactory, PepResponseFactory, PersistedResource, Subject
};
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PDP\{
    ArrayPolicyFinderFactory, CerberusEngine, Utility\ArrayProperties
};
use Cerberus\PIP\PipFinder;
use Ds\Set;
use Test\Document;

class VariableDefinitionMappingCest
{
    /** @var PepAgent */
    protected $pepAgent;

    public function _before(UnitTester $I)
    {
        require __DIR__ . '/../../_data/fixtures/PEP/testVariableDefinitionMapperProperties.php';
        $properties = new ArrayProperties($testVariableDefinitionMapperProperties);
        $this->pepAgent = (new PepAgentFactory($properties))->getPepAgent();
    }

    public function testDeny(UnitTester $I)
    {
        $subject = new Subject('subjectIdJSmith');
        $action = new Action('read');
        $resource = new PersistedResource('fileResolver', 'fileId12345');
        $response = $this->pepAgent->decide($subject, $action, $resource);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function testPermit(UnitTester $I)
    {
        // grant permission

        $subject = new Subject('subjectIdJSmith');
        $action = new Action('read');
        $resource = new PersistedResource('fileResolver', 'fileId12345');

        $response = $this->pepAgent->decide($subject, $action, $resource);
        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }
}