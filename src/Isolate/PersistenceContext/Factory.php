<?php

namespace Isolate\PersistenceContext;

interface Factory 
{
    /**
     * @param string $name
     */
    public function create($name);
}
