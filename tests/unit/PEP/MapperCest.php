<?php
declare(strict_types = 1);

use Cerberus\PEP\{
    Action, ObjectMapper, PepAgent, PepRequest, Subject
};
use Ds\Set;

class MapperCest
{
    /** @var PepAgent */
    protected $pepAgent;

    public function _before(UnitTester $I)
    {
        $this->pepAgent = new PepAgent();
    }

    public function testPermit(UnitTester $I)
    {
        $subject = new Subject("John Smith");
        $subject->addAttribute("urn:oasis:names:tc:xacml:1.0:$subject:role-id", "ROLE_DOCUMENT_WRITER");

        $action = new Action("write");

        $doc = new Document(1, "OnBoarding Document", "ABC Corporation", "John Smith");
        $response = $this->pepAgent->decide($subject, $action, $doc);
        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }


    public function testNotApplicable(UnitTester $I)
    {
        $subject = new Subject("John Smith");
        $subject->addAttribute("urn:oasis:names:tc:xacml:1.0:$subject:role-id", "ROLE_DOCUMENT_WRITER");

        $action = new Action("write");
        $doc = new Document(2, "OnBoarding Document", "XYZ Corporation", "Jim Doe");
        $response = $this->pepAgent->decide($subject, $action, $doc);
        $I->assertNotNull($response);
        $I->assertFalse(false, $response->allowed());
    }

    public function testMix(UnitTester $I)
    {
//        @Test(expected = PepException.class)

        $subject = new Subject("John Smith");
        $subject->addAttribute("urn:oasis:names:tc:xacml:1.0:$subject:role-id", "ROLE_DOCUMENT_WRITER");

        $action = new Action("write");

        $doc1 = new Document(1, "OnBoarding Document", "ABC Corporation", "John Smith");
        $doc2 = new Document(2, "OnBoarding Document", "XYZ Corporation", "Jim Doe");
        $resourceList = new Set();
        $resourceList->add($doc1);
        $resourceList->add($doc2);

        $response = $this->pepAgent->decide($subject, $action, $resourceList);
        $I->assertNotNull($response);
        $I->assertEquals(false, $response->allowed());
        $response->allowed();
    }

    public function testVarArgsPermit(UnitTester $I)
    {
        $subject = new Subject("John Smith");
        $subject->addAttribute("urn:oasis:names:tc:xacml:1.0:$subject:role-id", "ROLE_DOCUMENT_READER");
        $businessContext = new BusinessRequestContext("USA", "05:00 EST");

        $action = new Action("read");
        $resources = new Set();
        $resources->add(new Document(1, "OnBoarding Document", "XYZ Corporation", "Jim Doe"));
        $resources->add(new Client("XYZ Corporation", "USA"));

        $response = $this->pepAgent->decide($subject, $action, $resources, $businessContext);
        $I->assertNotNull($response);
        $I->assertEquals(true, $response->allowed());
    }

    public function testVarArgsDeny(UnitTester $I)
    {
        $subject = new Subject("John Smith");
        $subject->addAttribute("urn:oasis:names:tc:xacml:1.0:$subject:role-id", "ROLE_DOCUMENT_READER");
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

class Document
{
    protected $clientName;
    protected $documentId;
    protected $documentName;
    protected $documentOwner;

    public function __construct(integer $documentId, string $documentName, string $clientName, string $documentOwner)
    {
        $this->documentId = $documentId;
        $this->documentName = $documentName;
        $this->clientName = $clientName;
        $this->documentOwner = $documentOwner;
    }

    public function getDocumentId(): integer
    {
        return $this->documentId;
    }

    public function getDocumentName(): string
    {
        return $this->documentName;
    }

    public function getDocumentOwner(): string
    {
        return $this->documentOwner;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }
}

class DocumentMapper extends ObjectMapper
{
    public function map($document, PepRequest $pepRequest)
    {
        $resourceAttributes = $pepRequest->getPepRequestAttributes(XACML3 . ID_ATTRIBUTE_CATEGORY_RESOURCE);
        $resourceAttributes->addAttribute("resource:resource-id", $document->getDocumentId());
        $resourceAttributes->addAttribute("resource:resource-type", Document::class);
        $resourceAttributes->addAttribute("jpmc:document:document-name", $document->getDocumentName());
        $resourceAttributes->addAttribute("jpmc:document:client-name", $document->getClientName());
        $resourceAttributes->addAttribute("jpmc:document:document-owner", $document->getDocumentOwner());
    }
}