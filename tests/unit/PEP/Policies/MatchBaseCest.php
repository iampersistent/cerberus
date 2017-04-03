<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use Cerberus\PDP\Utility\ArrayProperties;
use Cerberus\PEP\{
    PepAgent, PepAgentFactory, Subject
};
use TestData\Document;
use UnitTester;

class MatchBaseCest
{
    /** @var string */
    protected $userId = '5';
    /** @var string */
    protected $policyPath;
    /** @var string[] */
    protected $configurationMappers = [
        'documentMapper',
    ];

    /** @var PepAgent */
    protected $pepAgent;
    /** @var Document */
    protected $document;
    /** @var Subject */
    protected $subject;

    public function _before(UnitTester $I)
    {
        $this->pepAgent = (new PepAgentFactory($this->getProperties()))->getPepAgent();
        $this->subject = new Subject($this->userId);
        $this->document = new Document(1, "OnBoarding Document", "ABC Corporation", $this->userId);
    }

    protected function getProperties(): ArrayProperties
    {
        $defaultsPath = codecept_data_dir('fixtures/PEP/defaultProperties.php');
        $defaults = require $defaultsPath;

        foreach($this->configurationMappers ?: [] as $mapper) {
            if (! pathinfo($mapper, PATHINFO_EXTENSION)) {
                $mapper .= '.php';
            }
            $defaults['pep']['mappers']['configurations'][] = codecept_data_dir('fixtures/PEP/Mappers/' . $mapper);
        }

        if (! pathinfo($this->policyPath, PATHINFO_EXTENSION)) {
            $this->policyPath .= '.php';
        }
        $defaults['rootPolicies'][] = codecept_data_dir('fixtures/PEP/Policies/' . $this->policyPath);

        return new ArrayProperties($defaults);
    }
}