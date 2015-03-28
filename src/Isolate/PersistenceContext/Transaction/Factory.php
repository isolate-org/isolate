<?php

namespace Isolate\PersistenceContext\Transaction;

use Isolate\PersistenceContext\Transaction;
use Isolate\UnitOfWork\UnitOfWork;

interface Factory
{
    /**
     * @param UnitOfWork $unitOfWork
     * @return Transaction
     */
    public function create(UnitOfWork $unitOfWork);
}
