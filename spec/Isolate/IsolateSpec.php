<?php

namespace spec\Isolate;

use Isolate\PersistenceContext;
use Isolate\PersistenceContext\Factory;
use Isolate\PersistenceContext\Transaction;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IsolateSpec extends ObjectBehavior
{
    function let(Factory $contextFactory, PersistenceContext $context, Transaction $transaction)
    {
        $contextFactory->create(Argument::type('Isolate\PersistenceContext\Name'))->willReturn($context);
        $context->openTransaction()->willReturn($transaction);
        
        $this->beConstructedWith($contextFactory);
    }

    function it_creates_context_only_when_it_does_not_exists(Factory $contextFactory, PersistenceContext $context)
    {
        $contextFactory->create(Argument::type('Isolate\PersistenceContext\Name'))->shouldBecalledTimes(1);

        $this->getContext('database')->shouldReturnAnInstanceOf('Isolate\\PersistenceContext');
        $this->getContext('database')->shouldReturnAnInstanceOf('Isolate\\PersistenceContext');
    }
 
    function it_open_transaction_on_context()
    {
        $this->openTransaction()->shouldReturnAnInstanceOf("Isolate\\PersistenceContext\\Transaction");
    }

    function it_close_transaction_on_context(PersistenceContext $context)
    {
        $context->closeTransaction()->shouldBeCalled();
        
        $this->closeTransaction();
    }
    
    function it_knows_if_has_open_transaction(PersistenceContext $context)
    {
        $context->hasOpenTransaction()->willReturn(true);
        
        $this->hasOpenTransaction()->shouldReturn(true);
    }

    function it_returns_open_transaction(PersistenceContext $context, Transaction $transaction)
    {
        $context->getTransaction()->willReturn($transaction);
        
        $this->getTransaction()->shouldReturnAnInstanceOf("Isolate\\PersistenceContext\\Transaction");
    }
}
