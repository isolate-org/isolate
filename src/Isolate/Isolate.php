<?php

namespace Isolate;

use Isolate\PersistenceContext\Factory;

final class Isolate
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
     * @param $name
     * @return PersistenceContext
     */
    public function getContext($name)
    {
        if (!array_key_exists($name, $this->contexts)) {
            $this->contexts[$name] = $this->contextFactory->create($name);
        }

        return $this->contexts[$name];
    }
}
