<?php
declare(strict_types = 1);

namespace Cerberus\PIP;

use Cerberus\Core\Status;
use Cerberus\PIP\Contract\PipEngine;
use Ds\Set;
use Exception;

class PipFinder
{
    /** @var PipEngine[]|Set */
    protected $pipEngines;

    public function __construct($engines = [])
    {
        $this->pipEngines = new Set($engines);
    }

    public function getAttributes(
        PipRequest $pipRequest,
        PipEngine $exclude = null,
        PipFinder $pipFinderParent = null
    ): PipResponse
    {
        $pipResponse = new PipResponse();
        $firstErrorStatus = null;
        foreach ($this->getPipEngines() as $pipEngine) {
            if ($pipEngine != $exclude) {
                try {
                    $pipEngineResponse = $pipEngine->getAttributes($pipRequest, $pipFinderParent);
                } catch (Exception $e) {
                    $pipEngineResponse = (new PipResponse())->setStatus(Status::createProcessingError());
                }

                if (! $pipEngineResponse->getStatus() || $pipEngineResponse->getStatus()->isOk()) {
                    $pipResponse->addAttributes($pipEngineResponse->getAttributes());
                } else {
                    if (! $firstErrorStatus) {
                        $firstErrorStatus = $pipEngineResponse->getStatus();
                    }
                }
            }
        }
        if ($firstErrorStatus && ! $pipResponse->getAttributes()) {
            $pipResponse->setStatus($firstErrorStatus);
        }

        return $pipResponse;
    }

    /**
     * Retrieves <code>Attribute</code>s that match the given
     * {@link org.apache.openaz.xacml.api.pip.PipRequest}. The
     * {@link org.apache.openaz.xacml.api.pip.PipResponse} should only include a single
     * {@link org.apache.openaz.xacml.api.Attribute} with {@link org.apache.openaz.xacml.api.AttributeValue}s
     * whose data type matches the request.
     *
     * @param pipRequest the <code>PipRequest</code> defining which <code>Attribute</code>s should be
     *            retrieved
     * @param excude the (optional) <code>PipEngine</code> to exclude from searches for the given
     *            <code>PipRequest</code>
     *
     * @return a {@link org.apache.openaz.xacml.pip.PipResponse} with the results of the request
     * @throws PipException if there is an error retrieving the <code>Attribute</code>s.
     */
    public function getMatchingAttributes(
        PipRequest $pipRequest,
        PipEngine $exclude,
        PipFinder $pipFinderParent = null
    ): PipResponse
    {
        return PipResponse . getMatchingResponse($pipRequest, $this->getAttributes($pipRequest, $exclude));
    }

    public function getPipEngines(): Set
    {
        return $this->pipEngines;
    }

    public function register(PipEngine $pipEngine)
    {
        $this->pipEngines->add($pipEngine);
    }
}