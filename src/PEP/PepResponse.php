<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Decision;
use Cerberus\Core\ObligationRouter;
use Cerberus\Core\Result;
use Cerberus\PEP\Exception\PepException;
use Ds\Map;

class PepResponse
{
    protected $errorMessage = null;
    protected $obligationRouter;
    /** @var Map */
    protected $obligations;
    /** @var Result */
    protected $result;

    public function __construct(ObligationRouter $obligationRouter, Result $result)
    {
        $this->obligationRouter = $obligationRouter;
        $this->obligations = new Map();
        $this->result = $result;
    }

    public function allowed(): bool
    {
        $this->obligationRouter->routeObligations($this->getObligations());
        
        switch ($this->result->getDecision()->getValue()) {
            case Decision::PERMIT:
                return true;
            case Decision::DENY:
                return false;
            case Decision::NOT_APPLICABLE:
                return false;
                //return enforceBehavior($this->pepConfig->getNotApplicableBehavior(), "Not Applicable");
            // TODO: Handle various indeterminate status codes.
            case Decision::INDETERMINATE:
            case Decision::INDETERMINATE_DENY:
            case Decision::INDETERMINATE_DENY_PERMIT:
            case Decision::INDETERMINATE_PERMIT:
                $status = $this->result->getStatus();
                $this->errorMessage = sprintf('Decision: Indeterminate, Status Code: %s, Status Message: %s',
                    $status->getStatusCode(), $status->getStatusMessage());
                return false;
            default:
                throw new PepException('Invalid response from PDP');
        }
    }

    public function getObligations(): Map
    {
        return $this->obligations;
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

    public function hasError(): bool
    {
        return (bool) $this->errorMessage;
    }

    public function getErrorMessage(): string
    {
        return (string) $this->errorMessage;
    }
}
