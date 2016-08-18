<?php
declare(strict_types = 1);

namespace Cerberus\Pip;

use Ds\Collection;

class PipFinder
{
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
    public function getAttributes(PipRequest $pipRequest, PipEngine $exclude, PipFinder $pipFinderParent = null): PipResponse
    {

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

    }

    public function getPipEngines(): Collection
    {
        
    }
}