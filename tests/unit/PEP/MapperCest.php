<?php
declare(strict_types = 1);

use Cerberus\Core\Identifier;
use Cerberus\PDP\{
    Utility\ArrayProperties
};
use Cerberus\PEP\{
    Action\Action, ObjectMapper, PepAgent, PepAgentFactory, PepRequest, Subject
};
use Ds\Set;
use Test\Document;

class MapperCest
{
    /** @var PepAgent */
    protected $pepAgent;

    public function _before(UnitTester $I)
    {
        $properties = require __DIR__ . '/../../_data/fixtures/PEP/testMapperProperties.php';
        $properties = new ArrayProperties($properties);
        $this->pepAgent = (new PepAgentFactory($properties))->getPepAgent();
    }

    public function testPermit(UnitTester $I)
    {
        $subject = new Subject("John Smith");
        $subject->addAttribute("subject:role-id", "ROLE_DOCUMENT_WRITER");

        $action = new Action("write");

        $doc = new Document(1, "OnBoarding Document", "ABC Corporation", "John Smith");
        $response = $this->pepAgent->decide($subject, $action, $doc);
        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }

    public function testNotApplicable(UnitTester $I)
    {
        $subject = new Subject("John Smith");
        $subject->addAttribute("subject:role-id", "ROLE_DOCUMENT_WRITER");

        $action = new Action("write");
        $doc = new Document(2, "OnBoarding Document", "XYZ Corporation", "Jim Doe");
        $response = $this->pepAgent->decide($subject, $action, $doc);
        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    /**
     * @skip
     */
    public function testMix(UnitTester $I)
    {
//        @Test(expected = PepException.class)

        $subject = new Subject("John Smith");
        $subject->addAttribute("subject:role-id", "ROLE_DOCUMENT_WRITER");

        $action = new Action("write");

        $doc1 = new Document(1, "OnBoarding Document", "ABC Corporation", "John Smith");
        $doc2 = new Document(2, "OnBoarding Document", "XYZ Corporation", "Jim Doe");
        $resourceList = new Set();
        $resourceList->add($doc1);
        $resourceList->add($doc2);

        $response = $this->pepAgent->decide($subject, $action, $resourceList);
        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
        $response->allowed();
    }

    /**
     * @skip
     */
    public function testVarArgsPermit(UnitTester $I)
    {
        $subject = new Subject("John Smith");
        $subject->addAttribute("subject:role-id", "ROLE_DOCUMENT_READER");
        $businessContext = new BusinessRequestContext("USA", "05:00 EST");

        $action = new Action("read");
        $resources = new Set();
        $resources->add(new Document(1, "OnBoarding Document", "XYZ Corporation", "Jim Doe"));
        $resources->add(new Client("XYZ Corporation", "USA"));

        $response = $this->pepAgent->decide($subject, $action, $resources, $businessContext);
        $I->assertNotNull($response);
        $I->assertEquals(true, $response->allowed());
    }


    /**
     * @skip
     */
    public function testVarArgsDeny(UnitTester $I)
    {
        $subject = new Subject("John Smith");
        $subject->addAttribute("subject:role-id", "ROLE_DOCUMENT_READER");
        $businessContext = new BusinessRequestContext("INDIA", "05:00 IST");

        $resources = new Set();
        $resources->add(new Document(2, "OnBoarding Document", "XYZ Corporation", "Jim Doe"));
        $resources->add(new Client("XYZ Corporation", "USA"));

        $action = new Action("write");

        $response = $this->pepAgent->decide($subject, $action, $resources, $businessContext);
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
        $resourceAttributes = $pepRequest->getPepRequestAttributes(Identifier::ATTRIBUTE_CATEGORY_RESOURCE);
        $resourceAttributes->addAttribute("resource:resource-id", $document->getDocumentId());
        $resourceAttributes->addAttribute("resource:resource-type", Document::class);
        $resourceAttributes->addAttribute("jpmc:document:document-name", $document->getDocumentName());
        $resourceAttributes->addAttribute("jpmc:document:client-name", $document->getClientName());
        $resourceAttributes->addAttribute("jpmc:document:document-owner", $document->getDocumentOwner());
    }
}
