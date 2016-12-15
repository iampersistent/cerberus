<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Contract;

use Cerberus\PIP\PipFinder;
use Cerberus\PIP\PipRequest;
use Cerberus\PIP\PipResponse;

interface PipEngine
{
    /**
     * Gets the <code>String</code> name identifying this <code>PIPEngine</code>. Names do not need to be
     * unique.
     *
     * @return the <code>String</code> name of this <code>PIPEngine</code>>
     */
    public function getName(): string;

    /**
     * Gets the <code>String</code> description of this <code>PIPEngine</code>.
     *
     * @return the <code>String</code> description of this <code>PIPEngine</code>.
     */
    public function getDescription(): string;

    /**
     * Returns a list of PIPRequests required by the Engine to return an attribute(s).
     *
     * @return Collection of required attributes
     */
    public function attributesRequired();

    /**
     * Returns a list of PIPRequest objects that the Engine can return.
     *
     * @return Collection of provided attributes
     */
    public function attributesProvided();

    /**
     * Retrieves <code>Attribute</code>s that match the given
     * {@link org.apache.openaz.xacml.api.pip.PIPRequest}. The
     * {@link org.apache.openaz.xacml.api.pip.PIPResponse} may contain multiple <code>Attribute</code>s and
     * they do not need to match the <code>PIPRequest</code>. In this way, a <code>PIPEngine</code> may
     * compute multiple related <code>Attribute</code>s at once.
     *
     * @param pipRequest the <code>PIPRequest</code> defining which <code>Attribute</code>s should be
     *            retrieved
     * @param pipFinder the <code>PIPFinder</code> to use for retrieving supporting attribute values
     *
     * @return a {@link org.apache.openaz.xacml.pip.PIPResponse} with the results of the request
     * @throws PIPException if there is an error retrieving the <code>Attribute</code>s.
     */
    public function getAttributes(PipRequest $pipRequest, PipFinder $pipFinder = null): PipResponse;
}