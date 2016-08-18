<?php
declare(strict_types = 1);

namespace Cerberus\Core;

class Response
{
    /**
     * Gets the <code>Collection</code> of {@link Result}s objects in this <code>Response</code>. If there are
     * no <code>Result</code>s, this method must return an empty <code>Collection</code>.
     *
     * @return the <code>Collection</code> of {@link Result}s objects in this <code>Response</code>.
     */
    public function getResults(): array
    {

    }

    /**
     * {@inheritDoc} Implementations of this interface must override the <code>equals</code> method with the
     * following semantics: Two <code>Response</code>s (<code>r1</code> and <code>r2</code>) are equal if:
     * {@code r1.getResults()} is pairwise equal to {@code r2.getResults()}
     */
    public function equals($obj): boolean
    {

    }
}