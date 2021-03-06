<?php

namespace Isolate\PersistenceContext;

use Isolate\Exception\InvalidArgumentException;

/**
 * @api
 */
final class Name
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function __construct($name)
    {
        if (!is_string($name) || empty($name)) {
            throw new InvalidArgumentException();
        }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
