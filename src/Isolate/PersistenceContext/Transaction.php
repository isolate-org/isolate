<?php

namespace Isolate\PersistenceContext;

/**
 * @api
 */
interface Transaction
{
    /**
     * @return void
     * 
     * @api
     */
    public function commit();

    /**
     * @return void
     * 
     * @api
     */
    public function rollback();

    /**
     * @param mixed $entity
     * @return boolean
     * 
     * @api
     */
    public function contains($entity);

    /**
     * @param mixed $entity
     * 
     * @api
     */
    public function persist($entity);

    /**
     * @param mixed $entity
     * 
     * @api
     */
    public function delete($entity);
}


