<?php

namespace Isolate;

use Isolate\PersistenceContext\Factory;
use Isolate\PersistenceContext\Name;

final class Isolate implements ContextRegistry
{
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
     * {inheritdoc}
     */
    public function getContext($name = self::DEFAULT_CONTEXT)
    {
        if (!array_key_exists($name, $this->contexts)) {
            $this->contexts[$name] = $this->contextFactory->create(new Name($name));
        }

        return $this->contexts[$name];
    }

    /**
     * {inheritdoc}
     */
    public function openTransaction($name = self::DEFAULT_CONTEXT)
    {
        return $this->getContext($name)->openTransaction();
    }

    /**
     * {inheritdoc}
     */
    public function closeTransaction($name = self::DEFAULT_CONTEXT)
    {
        $this->getContext($name)->closeTransaction();
    }

    /**
     * {inheritdoc}
     */
    public function hasOpenTransaction($name = self::DEFAULT_CONTEXT)
    {
        return $this->getContext($name)->hasOpenTransaction();
    }

    /**
     * {inheritdoc}
     */
    public function getTransaction($name = self::DEFAULT_CONTEXT)
    {
        return $this->getContext($name)->getTransaction();
    }
}
