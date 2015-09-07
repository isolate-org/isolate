<?php

namespace Isolate;

use Isolate\PersistenceContext\Factory;
use Isolate\PersistenceContext\Name;

/**
 * @api
 */
final class Isolate
{
    const DEFAULT_CONTEXT = 'isolate';

    /**
     * @var Factory
     */
    private $contextFactory;

    /**
     * @var array|PersistenceContext[]
     */
    private $contexts;
    
    /**
     * @param Factory $contextFactory
     */
    public function __construct(Factory $contextFactory)
    {
        $this->contextFactory = $contextFactory;
        $this->contexts = [];
    }

    /**
     * @param $name
     * @return PersistenceContext
     * 
     * @api
     */
    public function getContext($name = self::DEFAULT_CONTEXT)
    {
        if (!array_key_exists($name, $this->contexts)) {
            $this->contexts[$name] = $this->contextFactory->create(new Name($name));
        }

        return $this->contexts[$name];
    }

    /**
     * @param string $name
     * @return PersistenceContext\Transaction
     */
    public function openTransaction($name = self::DEFAULT_CONTEXT)
    {
        return $this->getContext($name)->openTransaction();
    }

    /**
     * @param string $name
     */
    public function closeTransaction($name = self::DEFAULT_CONTEXT)
    {
        $this->getContext($name)->closeTransaction();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasOpenTransaction($name = self::DEFAULT_CONTEXT)
    {
        return $this->getContext($name)->hasOpenTransaction();
    }

    /**
     * @param string $name
     * @return PersistenceContext\Transaction
     */
    public function getTransaction($name = self::DEFAULT_CONTEXT)
    {
        return $this->getContext($name)->getTransaction();
    }
}
