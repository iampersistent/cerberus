<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Contract;

use Cerberus\Core\Request;
use Cerberus\Core\Response;

interface PdpEngine
{
    /**
     * Evaluates the given Request using this PDPEngine's
     * Policy Sets to determine if the given Request is allowed.
     */
    public function decide(Request $request): Response;

    /**
     * Gets the Collection of ids that represent the profiles supported by this PdpEngine.
     */
    //public function getProfiles(): array;

    /**
     * Determines if this PdpEngine supports the given profile id.
     */
    //public function  hasProfile(string $profileId): bool;
}