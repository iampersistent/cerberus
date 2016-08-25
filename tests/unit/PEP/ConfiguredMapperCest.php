<?php
declare(strict_types = 1);

use Cerberus\Core\CategoryType;
use Cerberus\PEP\{ConfiguredMapper, MapperRegistry, PepRequest};
use Test\Document;

class ConfiguredMapperCest
{
    protected $testMap;

    public function __construct()
    {
        $this->testMap =     [
            'className'               => Document::class,
            'resource:resource-id'    => 'getDocumentId',
            'resource:resource-type'  => Document::class,
            'document:document-name'  => 'getDocumentName',
            'document:client-name'    => 'getClientName',
            'document:document-owner' => 'getDocumentOwner',
        ];
    }

    public function testMapping(UnitTester $I)
    {
        $mapper = new ConfiguredMapper($this->testMap);

        $I->assertSame(Document::class, $mapper->getMappedClass());

        $document = new Document(42, 'Test Document', 'Company', 'John Smith');
        $registry = new MapperRegistry();
        $registry->registerMapper($mapper);
        $pepRequest = new PepRequest($registry, $document);
        $attributes = $pepRequest->getPepRequestAttributes(CategoryType::ID_ATTRIBUTE_CATEGORY_RESOURCE);

        // assert some shit with the attributes
        $a= 0;
    }
}