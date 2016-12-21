<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Evaluation;

use Cerberus\Core\Request;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\PolicyDef;
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PDP\Policy\PolicyFinderResult;
use Cerberus\PDP\Policy\PolicySet;
use Cerberus\PIP\Contract\PipEngine;
use Cerberus\PIP\Engine\RequestEngine;
use Cerberus\PIP\Finder\RequestFinder;
use Cerberus\PIP\PipFinder;
use Cerberus\PIP\PipRequest;
use Cerberus\PIP\PipResponse;

class EvaluationContext extends PipFinder
{
    protected $functionDefinitionFactory;
    protected $pipFinder;
    protected $policyFinder;
    protected $request;
    /** @var Request */
    protected $requestFinder;

    public function __construct(Request $request, PolicyFinder $policyFinder, PipFinder $pipFinder, $functionDefinitionFactory)
    {
        $this->functionDefinitionFactory = $functionDefinitionFactory;
        $this->pipFinder = $pipFinder;
        $this->policyFinder = $policyFinder;
        $this->request = $request;

        if ($pipFinder instanceof RequestFinder) {
            $this->requestFinder = $pipFinder;
        } else {
            $this->requestFinder = new RequestFinder($pipFinder, new RequestEngine($request));
        }
        parent::__construct($pipFinder->getPipEngines());
    }

    public function getFunctionDefinitionFactory()
    {
        return $this->functionDefinitionFactory;
    }

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
    public function getPolicy($idReferenceMatch): Policy
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
    public function getPolicySet($idReferenceMatch): PolicySet
    {
        return $this->policyFinder->getPolicySet($this);
    }

    public function getAttributes(PipRequest $pipRequest, PipEngine $exclude = null, PipFinder $pipFinderParent = null): PipResponse
    {
        return $this->requestFinder->getAttributes($pipRequest, $exclude, $pipFinderParent);
    }
}