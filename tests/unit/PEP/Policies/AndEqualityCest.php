<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use FunctionalTester;
use Cerberus\PEP\Action\WriteAction;

class AndEqualityCest extends MatchBaseCest
{
    protected $policyPath = 'and-equality-policy';
    protected $configurationMappers = [
        'documentMapper',
    ];

    public function tryToGetNonPublicDocument(FunctionalTester $I)
    {
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function tryToGetPublicDocumentWithInvalidDocumentSize(FunctionalTester $I)
    {
        $this->document->setIsPublic(true);
        $this->document->setDocumentSize(0);

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function tryToGetNonPublicDocumentWithValidDocumentSize(FunctionalTester $I)
    {
        $this->document->setIsPublic(false);
        $this->document->setDocumentSize(123456);

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function tryToGetPublicDocumentWithValidDocumentSize(FunctionalTester $I)
    {
        $this->document->setIsPublic(true);
        $this->document->setDocumentSize(123456);

        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }
}