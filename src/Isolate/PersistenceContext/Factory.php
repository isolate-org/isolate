<?php

namespace Isolate\PersistenceContext;

use Isolate\PersistenceContext;

interface Factory
{
    /**
     * @param Name $name
     * @return PersistenceContext
     */
    public function create(Name $name);
}
