<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use Cerberus\PIP\Permission\MappedObject;
use TestData\Document;
use UnitTester;
use Cerberus\PEP\Action\WriteAction;

class DocumentIdEqualsFortyTwoCest extends MatchBaseCest
{
    protected $policyPath = 'document-id-equals-forty-two-policy';
    /** @var MappedObject */
    protected $object;

    public function testQueryOnEmptyRecord(UnitTester $I)
    {
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->alternateDocument);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function testQueryWithPermission(UnitTester $I)
    {
        // grant permission
        $this->object = new MappedObject([
            'resourceId'   => $this->alternateDocument->getDocumentId(),
            'resourceType' => Document::class,
            'subjectType'  => 'user',
            'subjectId'    => $this->userId,
            'actions'      => ['read', 'write'],
        ]);
        $this->repository->save($this->object);

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->alternateDocument);

        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }

    public function testQueryWithoutPermission(UnitTester $I)
    {
        $this->object->removeAction(new WriteAction());

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }
}