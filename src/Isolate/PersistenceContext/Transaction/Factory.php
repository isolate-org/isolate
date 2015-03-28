<?php

namespace Isolate\PersistenceContext\Transaction;

use Isolate\PersistenceContext\Transaction;

interface Factory
{
    /**
     * @return Transaction
     */
    public function create();
}
