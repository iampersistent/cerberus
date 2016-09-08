<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Decision;
use Cerberus\Core\ObligationRouter;
use Cerberus\Core\Result;
use Cerberus\PEP\Exception\PepException;

class PepResponse
{
    protected $obligationRouter;
    protected $result;

    public function __construct(ObligationRouter $obligationRouter, Result $result)
    {
        $this->obligationRouter = $obligationRouter;
        $this->result = $result;
    }
    /**
     * Returns the decision associated with the current result.
     *
     * @return true if the user was granted access to the resource, otherwise false
     * @throws PepException if the {@link PepResponseBehavior} configured in the {@link PepResponseFactory}
     *             indicates that for the response should be thrown
     */
    public function allowed(): bool
    {
//        if (obligationRouter != null) {
//            obligationRouter . routeObligations(getObligations());
//        }
        switch ($this->result->getDecision()) {
            case Decision::PERMIT:
                return true;
            case Decision::DENY:
                return false;
            case Decision::NOT_APPLICABLE:
            case Decision::INDETERMINATE:
            case Decision::INDETERMINATE_DENY:
            case Decision::INDETERMINATE_DENY_PERMIT:
            case Decision::INDETERMINATE_PERMIT:
                $status = $this->result->getStatus();
                $message = sprintf("Decision: Indeterminate, Status Code: %s, Status Message: %s",
                    $status->getStatusCode(), $status->getStatusMessage());
                throw new PepException($message);
            default:
                throw new PepException("Invalid response from PDP");
        }
    }

    /**
     * Return the set of {@link org.apache.openaz.pepapi.Obligation}s associated with the
     * current result indexed by ObligationId.
     * @return a Map of ObligationId, Obligation pairs Map<String, Obligation>
     * @throws PepException
     * @see org.apache.openaz.pepapi.Obligation#getId()
     */
    public function getObligations(): array
    {

    }

    /**
     * Return the set of {@link org.apache.openaz.pepapi.Advice}s associated with the
     * current result indexed by adviceId.
     * @return a Map of adviceId, Advice pairs
     * @throws PepException
     * @see org.apache.openaz.pepapi.Advice#getId()
     */
    public function getAdvices() : array
    {

    }

    /**
     * Return the object association that is tied to the current result. The association is the same object
     * that was used to create the PepRequest and may be used to correlate the PepResponse results with the
     * association pairs that were used to create the PepRequest.
     *
     * @return an object that was used as the action-resource in the PepRequest
     * @throws PepException
     */
    public function getAssociation()
    {

    }

    /**
     * @return
     */
    public function getAttributes(): array
    {

    }

    /**
     *
     * @return Map<Identifier, Collection<Attribute>>
     */
    public function getAttributesByCategory(): array
    {

    }
}