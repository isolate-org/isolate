<?php

namespace Isolate;

use Isolate\PersistenceContext\Transaction;

/**
 * @api
 */
interface ContextRegistry
{
    const DEFAULT_CONTEXT = 'isolate';
    
    /**
     * @param $name
     * @return PersistenceContext
     *
     * @api
     */
    public function getContext($name = self::DEFAULT_CONTEXT);

    /**
     * @param string $name
     * @return mixed
     * 
     * @api
     */
    public function openTransaction($name = self::DEFAULT_CONTEXT);

    /**
     * @param string $name
     * @return void
     * 
     * @api
     */
    public function closeTransaction($name = self::DEFAULT_CONTEXT);

    /**
     * @param string $name
     * @return bool
     * 
     * @api
     */
    public function hasOpenTransaction($name = self::DEFAULT_CONTEXT);

    /**
     * @param string $name
     * @return Transaction
     * 
     * @api
     */
    public function getTransaction($name = self::DEFAULT_CONTEXT);
}