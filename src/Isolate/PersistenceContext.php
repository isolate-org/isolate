<?php

namespace Isolate;

use Isolate\PersistenceContext\Name;
use Isolate\PersistenceContext\Transaction;

/**
 * @api
 */
interface PersistenceContext
{
    /**
     * @return Name
     * 
     * @api
     */
    public function getName();

    /**
     * @return Transaction
     * 
     * @api
     */
    public function openTransaction();

    /**
     * @return boolean
     * 
     * @api
     */
    public function hasOpenTransaction();

    /**
     * @return Transaction
     * 
     * @api
     */
    public function getTransaction();

    /**
     * @return void
     * 
     * @api
     */
    public function closeTransaction();
}
