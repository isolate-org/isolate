<?php

namespace Isolate;

use Isolate\PersistenceContext\Factory;
use Isolate\PersistenceContext\Name;

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
     */
    public function getContext($name = self::DEFAULT_CONTEXT)
    {
        if (!array_key_exists($name, $this->contexts)) {
            $this->contexts[$name] = $this->contextFactory->create(new Name($name));
        }

        return $this->contexts[$name];
    }
}
