<?php
declare(strict_types = 1);

namespace Cerberus\PIP;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Ds\Collection;
use Exception;

class PipFinder
{
    /** @var PipEngine[] */
    protected $pipEngines;

    /**
     * Retrieves <code>Attribute</code>s that based on the given
     * {@link org.apache.openaz.xacml.api.pip.PipRequest}. The
     * {@link org.apache.openaz.xacml.api.pip.PipResponse} may contain multiple <code>Attribute</code>s and
     * they do not need to match the <code>PipRequest</code>. In this way, a <code>PipFinder</code> may
     * compute multiple related <code>Attribute</code>s at once.
     *
     * @param pipRequest the <code>PipRequest</code> defining which <code>Attribute</code>s should be
     *            retrieved
     * @param excude the (optional) <code>PipEngine</code> to exclude from searches for the given
     *            <code>PipRequest</code>
     *
     * @return a {@link org.apache.openaz.xacml.pip.PipResponse} with the results of the request
     * @throws PipException if there is an error retrieving the <code>Attribute</code>s.
     */
    public function getAttributes(PipRequest $pipRequest, PipEngine $exclude = null, PipFinder $pipFinderParent = null): PipResponse
    {
        $pipResponse = new PipResponse();
        $firstErrorStatus = null;
        foreach ($this->pipEngines as $pipEngine) {
            if ($pipEngine != $exclude) {
                $pipEngineReponse = null;
                try {
                    $pipEngineReponse = $pipEngine->getAttributes($pipRequest, $pipFinderParent);
                } catch (Exception $e) {
                    $pipEngineReponse = new PipResponse(new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR()));
                }
                if ($pipEngineReponse) {
                    if ($pipEngineReponse->getStatus() == null || $pipEngineReponse->getStatus()->isOk()) {
                        $pipResponse->addAttributes($pipEngineReponse->getAttributes());
                    } else if ($firstErrorStatus == null) {
                        $firstErrorStatus = $pipEngineReponse->getStatus();
                    }
                }
            }
        }
        if ($pipResponse->getAttributes()->isEmpty() && $firstErrorStatus != null) {
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
    public function getMatchingAttributes(PipRequest $pipRequest, PipEngine $exclude, PipFinder $pipFinderParent = null): PipResponse
    {
        return PipResponse.getMatchingResponse(pipRequest, $this->getAttributes(pipRequest, exclude));
    }

    public function getPipEngines(): Collection
    {

    }

    /**
     * Registers a new <code>PIPEngine</code> with this <code>EngineFinder</code>.
     *
     * @param pipEngine the <code>PIPEngine</code> to register
     */
    public function register(PipEngine $pipEngine)
    {
        $this->pipEngines[] = $pipEngine;
    }
}