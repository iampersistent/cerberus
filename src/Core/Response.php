<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Set;

class Response
{
    /** @var Set */
    protected $results;

    public function __construct($result = null)
    {
        $this->results = new Set();

        if ($result instanceof Status) {
            $this->add(new Result($result));
        }
        if ($result instanceof Result) {
            $this->add($result);
        }
    }

    public function add(Result $result)
    {
        $this->results->add($result);
    }

    /**
     * Gets the <code>Collection</code> of {@link Result}s objects in this <code>Response</code>. If there are
     * no <code>Result</code>s, this method must return an empty <code>Collection</code>.
     */
    public function getResults()
    {
        return $this->results;
    }
}