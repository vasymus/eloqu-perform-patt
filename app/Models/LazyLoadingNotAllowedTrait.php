<?php

namespace App\Models;

use LogicException;

trait LazyLoadingNotAllowedTrait
{
    /**
     * Get a relationship value from a method.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws \LogicException
     */
    public function getRelationshipFromMethod($name)
    {
        $class = get_class($this);

        throw new LogicException("Lazy-loading relationships is not allowed ($class::$name).");
    }
}
