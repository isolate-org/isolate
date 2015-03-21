<?php

namespace Isolate;

use Isolate\PersistenceContext\Transaction;

interface PersistenceContext
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return Transaction
     */
    public function openTransaction();

    /**
     * @return boolean
     */
    public function hasOpenTransaction();

    /**
     * @return Transaction
     */
    public function getTransaction();

    /**
     * @return void
     */
    public function closeTransaction();
}
