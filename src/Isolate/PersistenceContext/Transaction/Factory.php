<?php

namespace Isolate\PersistenceContext\Transaction;

use Isolate\PersistenceContext;
use Isolate\PersistenceContext\Transaction;

interface Factory
{
    /**
     * @param PersistenceContext $context
     * @return Transaction
     */
    public function create(PersistenceContext $context);
}
