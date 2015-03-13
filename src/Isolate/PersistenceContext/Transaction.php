<?php

namespace Isolate\PersistenceContext;

interface Transaction
{
    /**
     * @return void
     */
    public function commit();

    /**
     * @return void
     */
    public function rollback();

    /**
     * @param mixed $entity
     * @return boolean
     */
    public function contains($entity);

    /**
     * @param mixed $entity
     */
    public function persist($entity);

    /**
     * @param mixed $entity
     */
    public function delete($entity);
}


