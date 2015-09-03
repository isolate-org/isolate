<?php

namespace Isolate\PersistenceContext;

use Isolate\PersistenceContext;

/**
 * @api
 */
interface Factory
{
    /**
     * @param Name $name
     * @return PersistenceContext
     * 
     * @api
     */
    public function create(Name $name);
}
