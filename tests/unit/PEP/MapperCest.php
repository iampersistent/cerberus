<?php
declare(strict_types = 1);

namespace Test\Unit\PEP;

use UnitTester;
use Cerberus\Core\Enums\{
    AttributeCategoryIdentifier, ResourceIdentifier
};
use Cerberus\PEP\{
    Action\Action, Action\WriteAction, ObjectMapper, PepRequest
};
use Ds\Set;
use Test\Unit\PEP\Policies\MatchBaseCest;
use TestData\Document;

class MapperCest extends MatchBaseCest
{
    protected $userId = 'John Smith';
    protected $policyPath = '../testPolicy004';
    protected $configurationMappers = [
        'documentMapper'
    ];
    /** @var Document */
    protected $alternateDocument;

    public function _before(UnitTester $I)
    {
        parent::_before($I);

        $this->subject->addAttribute("SubjectIdentifier::ROLE_ID", "ROLE_DOCUMENT_WRITER");

        $this->alternateDocument = new Document(2, "OnBoarding Document", "XYZ Corporation", "Jim Doe");
    }

    public function testPermit(UnitTester $I)
    {
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);
        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }

    public function testNotApplicable(UnitTester $I)
    {
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->alternateDocument);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    /**
     * @skip
     */
    public function testMix(UnitTester $I)
    {
//        @Test(expected = PepException.class)

        $resourceList = new Set();
        $resourceList->add($this->document);
        $resourceList->add($this->alternateDocument);

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $resourceList);
        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
        $response->allowed();
    }

    /**
     * @skip
     */
    public function testVarArgsPermit(UnitTester $I)
    {
        $this->subject->addAttribute("SubjectIdentifier::ROLE_ID", "ROLE_DOCUMENT_READER");
        $businessContext = new BusinessRequestContext("USA", "05:00 EST");

        $action = new Action("read");
        $resources = new Set();
        $resources->add(new Document(1, "OnBoarding Document", "XYZ Corporation", "Jim Doe"));
        $resources->add(new Client("XYZ Corporation", "USA"));

        $response = $this->pepAgent->decide($this->subject, $action, $resources, $businessContext);
        $I->assertNotNull($response);
        $I->assertEquals(true, $response->allowed());
    }


    /**
     * @skip
     */
    public function testVarArgsDeny(UnitTester $I)
    {
        $this->subject->addAttribute("SubjectIdentifier::ROLE_ID", "ROLE_DOCUMENT_READER");
        $businessContext = new BusinessRequestContext("INDIA", "05:00 IST");

        $resources = new Set();
        $resources->add(new Document(2, "OnBoarding Document", "XYZ Corporation", "Jim Doe"));
        $resources->add(new Client("XYZ Corporation", "USA"));

        $action = new Action("write");

        $response = $this->pepAgent->decide($this->subject, $action, $resources, $businessContext);
        $I->assertNotNull($response);
        $I->assertEquals(false, $response->allowed());
    }
}

class DocumentMapper extends ObjectMapper
{
    /**
     * @param Document   $document
     * @param PepRequest $pepRequest
     */
    public function map($document, PepRequest $pepRequest)
    {
        $resourceAttributes = $pepRequest->getPepRequestAttributes(AttributeCategoryIdentifier::RESOURCE);
        $resourceAttributes->addAttribute(ResourceIdentifier::RESOURCE_ID, $document->getDocumentId());
        $resourceAttributes->addAttribute(ResourceIdentifier::RESOURCE_TYPE, Document::class);
        $resourceAttributes->addAttribute("jpmc:document:document-name", $document->getDocumentName());
        $resourceAttributes->addAttribute("jpmc:document:client-name", $document->getClientName());
        $resourceAttributes->addAttribute("jpmc:document:document-owner", $document->getDocumentOwner());
    }
}
