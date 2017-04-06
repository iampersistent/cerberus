<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use UnitTester;
use Cerberus\PEP\Action\ReadAction;
use Cerberus\PEP\ResourceObject;
use TestData\Document;
use Cerberus\PEP\Action\WriteAction;
use Cerberus\PEP\Subject;

class DesignatorOrSubjectEqualsCest extends MatchBaseCest
{
    protected $policyPath = 'designator-or-subject-equals-policy';
    protected $configurationMappers = [
        'documentMapper',
    ];

    public function testNonPublicDocument(UnitTester $I)
    {
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function testPublicDocument(UnitTester $I)
    {
        $this->document->setIsPublic(true);

        // write is not allowed
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);
        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
        // read is allowed
        $response = $this->pepAgent->decide($this->subject, new ReadAction(), $this->document);
        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());

        // random subject
        // write is not allowed
        $response = $this->pepAgent->decide(new Subject('-1'), new WriteAction(), $this->document);
        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
        // read is allowed
        $response = $this->pepAgent->decide(new Subject('-1'), new ReadAction(), $this->document);
        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }

    public function testGrantedDocument(UnitTester $I)
    {
        $this->document->setIsPublic(false);

        $resource = new ResourceObject(Document::class, $this->document->getDocumentId());
        $this->addRecord($resource, $this->subject, ['read', 'write']);

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);
        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());

        // random subject
        $response = $this->pepAgent->decide(new Subject('-1'), new WriteAction(), $this->document);
        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }
}