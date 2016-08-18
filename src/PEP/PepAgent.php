<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\PDP\Contract\PdpEngine;
use Cerberus\PDP\Exception\PDPException;
use Cerberus\PEP\Exception\PepException;

class PepAgent
{
    public function __construct(PdpEngine $pdpEngine)
    {

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
        return decide(Subject.newInstance(subjectId), Action.newInstance(actionId),
            Resource.newInstance(resourceId));
    }

    /**
     * Returns an authorization decision for the given collection of Domain Objects each with it's own
     * ObjectMapper instance. Java Primitives/Wrappers or other Standard types (except Collections) are not
     * supported out of the box. However, client applications may enforce their own rules as they see fit by
     * providing Custom ObjectMapper(s) for these types.
     *
     * @param objects
     *
     * @return
     * @throws PepException - if an appropriate ObjectMapper cannot be found. - if the underlying AzService
     *             instance/PDP throws an exception - if the PepAgent is configured to throw PepException for
     *             "Indeterminate" or "Not Applicable" decisions.
     */
    public function decide(array $objects): PepResponse
    {
        $pepResponses = [];
//        Request $request = pepRequest.getWrappedRequest();

        // Log request
//        if (logger.isDebugEnabled()) {
//            logRequest(request);
//        }

        try {
            $response = $this->pdpEngine->decide($request);
        } catch (PDPException $e) {
        logger.error(e);
        throw new PepException($e);
    }

        // Log the response
        if (logger.isDebugEnabled()) {
            logResponse(response);
        }

        for (Result result : response.getResults()) {
            pepResponses.add(pepResponseFactory.newPepResponse(result));
        }

        return $pepResponses;
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
}