<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\ObligationRouter;
use Cerberus\PDP\Contract\PdpEngine;
use Cerberus\PDP\Exception\PDPException;
use Cerberus\PDP\Utility\Properties;
use Cerberus\PEP\Exception\PepException;

class PepAgent
{
    protected $pepConfig;
    /** @var PepRequestFactory */
    protected $pepRequestFactory;
    /** @var PepResponseFactory */
    protected $pepResponseFactory;
    /** @var PdpEngine */
    protected $pdpEngine;

    public function __construct(Properties $properties, PepConfig $pepConfig, PdpEngine $pdpEngine)
    {
        $this->pdpEngine = $pdpEngine;
        $this->pepConfig = $pepConfig;

        $mappingRegistry = new MapperRegistry($properties);
        $obligationRouter = new ObligationRouter();
        $this->pepRequestFactory = new PepRequestFactory($mappingRegistry);
        $this->pepResponseFactory = new PepResponseFactory($obligationRouter);
    }

    /**
     * Returns a authorization decision for the given subjectId, actionId, resourceId strings.
     *
     * @param subjectId
     * @param actionId
     * @param resourceId
     *
     * @return
     * @throws PepException - if an appropriate ObjectMapper cannot be found. - if the underlying AzService
     *             instance/PDP throws an exception - if the PepAgent is configured to throw PepExceptions for
     *             "Indeterminate" or "Not Applicable" decisions.
     */
    public function simpleDecide(string $subjectId, string $actionId, string $resourceId): PepResponse
    {
//        return decide(Subject.newInstance(subjectId), Action.newInstance(actionId),
//            Resource.newInstance(resourceId));
    }

    public function decide($subject, $action, $resources): PepResponse
    {
        $request = $this->pepRequestFactory->newPepRequest($subject, $action, $resources);

        try {
            $response = $this->pdpEngine->decide($request);
        } catch (PDPException $e) {
            throw new PepException($e);
        }

        $result = $response->getResults()->first();

        return $this->pepResponseFactory->newPepResponse($result);
    }

    /**
     * Returns a PepResponse instance representing a collection of decisions, each of which corresponds to an
     * association. Each association represents a specific instance of Domain Object binding. A typical
     * example for an association would be an Action-Resource pair.
     *
     * @param associations a list of Domain Object bindings, each of which maps to a individual Request.
     * @param objects a collection of common Domain Objects shared across all Requests.
     *
     * @return PepResponse[]
     * @throws PepException - if an appropriate ObjectMapper cannot be found. - if the underlying service
     *             instance/PDP throws an exception - if the PepAgent is configured to throw PepExceptions for
     *             "Indeterminate" or "Not Applicable" decisions.
     */
    public function bulkDecide(array $associations, array $objects): array
    {

    }

    public function getPdpEngine(): PdpEngine
    {
        return $this->pdpEngine;
    }

    public function getPepConfig(): PepConfig
    {
        return $this->pepConfig;
    }
}