<?php

namespace spec\Isolate;

use Isolate\PersistenceContext;
use Isolate\PersistenceContext\Factory;
use Isolate\PersistenceContext\Transaction;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IsolateSpec extends ObjectBehavior
{
    function let(Factory $contextFactory)
    {
        $this->beConstructedWith($contextFactory);
    }

    function it_creates_context_only_when_it_does_not_exists(Factory $contextFactory, PersistenceContext $context)
    {
        $contextFactory->create(Argument::type('Isolate\PersistenceContext\Name'))->willReturn($context);
        $contextFactory->create(Argument::type('Isolate\PersistenceContext\Name'))->shouldBecalledTimes(1);

        $this->getContext('database')->shouldReturnAnInstanceOf('Isolate\PersistenceContext');
        $this->getContext('database')->shouldReturnAnInstanceOf('Isolate\PersistenceContext');
    }
}
