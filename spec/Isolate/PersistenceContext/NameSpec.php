<?php

namespace spec\Isolate\PersistenceContext;

use Isolate\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameSpec extends ObjectBehavior
{
    function it_throws_exception_when_created_from_non_string()
    {
        $this->shouldThrow(
            new InvalidArgumentException()
        )->during("__construct", [new \stdClass()]);
    }

    function it_throws_exception_when_created_from_empty_string()
    {
        $this->shouldThrow(
            new InvalidArgumentException()
        )->during("__construct", [""]);
    }
}
