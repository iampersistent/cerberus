<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use UnitTester;
use Cerberus\PEP\Action\WriteAction;

class AndEqualityCest extends MatchBaseCest
{
    protected $policyPath = 'and-equality-policy';
    protected $configurationMappers = [
        'documentMapper',
    ];

    public function tryToGetNonPublicDocument(UnitTester $I)
    {
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function tryToGetPublicDocumentWithInvalidDocumentSize(UnitTester $I)
    {
        $this->document->setIsPublic(true);
        $this->document->setDocumentSize(0);

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function tryToGetNonPublicDocumentWithValidDocumentSize(UnitTester $I)
    {
        $this->document->setIsPublic(false);
        $this->document->setDocumentSize(123456);

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function tryToGetPublicDocumentWithValidDocumentSize(UnitTester $I)
    {
        $this->document->setIsPublic(true);
        $this->document->setDocumentSize(123456);

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }
}