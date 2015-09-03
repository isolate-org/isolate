<?php

namespace Isolate\PersistenceContext\Transaction;

use Isolate\PersistenceContext;
use Isolate\PersistenceContext\Transaction;

/**
 * @api
 */
interface Factory
{
    /**
     * @param PersistenceContext $context
     * @return Transaction
     * 
     * @api
     */
    public function create(PersistenceContext $context);
}
