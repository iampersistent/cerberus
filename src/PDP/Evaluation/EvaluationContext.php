<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Evaluation;

use Cerberus\Core\Request;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\PolicyDef;
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PDP\Policy\PolicySet;
use Cerberus\PIP\PipFinder;
use Cerberus\PIP\PipRequest;
use Cerberus\PIP\PipResponse;

class EvaluationContext extends PipFinder
{
    protected $pipFinder;
    protected $policyFinder;
    protected $request;

    public function __construct(Request $request, PolicyFinder $policyFinder, PipFinder $pipFinder)
    {
        $this->pipFinder = $pipFinder;
        $this->policyFinder = $policyFinder;
        $this->request = $request;
    }

    /**
     * Gets the original <code>Request</code> provided to the <code>ATTPDPEngine</code>'s <code>decide</code>
     * method.
     *
     * @return the <code>Request</code> provided to the <code>ATTPDPEngine</code>'s <code>decide</code>
     *         method.
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getRootPolicyDef(): PolicyFinderResult
    {
        return $this->policyFinder->getRootPolicyDef($this);
    }

    /**
     * Gets the {@link org.apache.openaz.xacml.pdp.policy.Policy} that matches the given
     * {@link org.apache.openaz.xacml.api.IdReferenceMatch}.
     *
     * @param idReferenceMatch the <code>IdReferenceMatch</code> to search for
     * @return a <code>PolicyFinderResult</code> with the <code>Policy</code> matching the given
     *         <code>IdReferenceMatch</code>
     */
    public function getPolicy(IdReferenceMatch $idReferenceMatch): Policy
    {
        return $this->policyFinder->getPolicy($this);

    }

    /**
     * Gets the {@link org.apache.openaz.xacml.pdp.policy.PolicySet} that matches the given
     * {@link org.apache.openaz.xacml.api.IdReferenceMatch}.
     *
     * @param idReferenceMatch the <code>IdReferenceMatch</code> to search for
     * @return a <code>PolicyFinderResult</code> with the <code>PolicySet</code> matching the given
     *         <code>IdReferenceMatch</code>.
     */
    public function getPolicySet(IdReferenceMatch $idReferenceMatch): PolicySet
    {
        return $this->policyFinder->getPolicySet($this);
    }

    /**
     * Gets the {@link org.apache.openaz.xacml.api.pip.PIPResponse} containing
     * {@link org.apache.openaz.xacml.api.Attribute}s that match the given
     * {@link org.apache.openaz.xacml.api.pip.PIPRequest} from this <code>EvaluationContext</code>.
     *
     * @param pipRequest the <code>PIPRequest</code> specifying which <code>Attribute</code>s to retrieve
     * @return the <code>PIPResponse</code> containing the {@link org.apache.openaz.xacml.api.Status} and
     *         <code>Attribute</code>s
     * @throws EvaluationException if there is an error retrieving the <code>Attribute</code>s
     */
//    public function getAttributes(PipRequest $pipRequest): PipResponse
//    {
//
//    }
}