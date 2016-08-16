<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Contract;

interface PDPEngine
{
    /**
     * Evaluates the given Request using this PDPEngine's
     * Policy Sets to determine if the given Request is allowed.
     */
    public function decide(Request $request): Response;

    /**
     * Gets the Collection of ids that represent the profiles supported by this PDPEngine<.
     */
    public function getProfiles(): array;

    /**
     * Determines if this PDPEngine supports the given profile id.
     */
    public function  hasProfile(string $profileId): boolean;
}