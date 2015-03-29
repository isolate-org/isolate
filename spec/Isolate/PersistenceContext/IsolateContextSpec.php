<?php

namespace spec\Isolate\PersistenceContext;

use Isolate\Exception\NotClosedTransactionException;
use Isolate\Exception\NotOpenedTransactionException;
use Isolate\PersistenceContext\Name;
use Isolate\PersistenceContext\Transaction;
use Isolate\PersistenceContext\Transaction\Factory as TransactionFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IsolateContextSpec extends ObjectBehavior
{
    function let(TransactionFactory $transactionFactory)
    {
        $this->beConstructedWith(new Name('isolate'), $transactionFactory);
    }

    function it_open_transactions(TransactionFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create($this)->willReturn($transaction);

        $this->openTransaction()->shouldReturn($transaction);
    }

    function it_throws_exception_when_opening_transaction_before_closing_old(TransactionFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create($this)->willReturn($transaction);

        $this->openTransaction()->shouldReturn($transaction);

        $this->shouldThrow(new NotClosedTransactionException())
            ->during('openTransaction');
    }

    function it_throws_exception_when_closing_not_opened_transaction()
    {
        $this->shouldThrow(new NotOpenedTransactionException())
            ->during('closeTransaction');
    }

    function it_close_opened_transaction(TransactionFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create($this)->willReturn($transaction);

        $transaction->commit()->shouldBeCalled();
        $this->openTransaction();

        $this->closeTransaction();
    }

    function it_throws_exception_when_accessing_not_opened_transaction()
    {
        $this->shouldThrow(new NotOpenedTransactionException())
            ->during('getTransaction');
    }

    function it_returns_opened_transaction(TransactionFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create($this)->willReturn($transaction);

        $this->openTransaction();

        $this->getTransaction()->shouldReturn($transaction);
    }

    function it_knows_whenever_transaction_is_open_or_not(TransactionFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create($this)->willReturn($transaction);

        $this->hasOpenTransaction()->shouldReturn(false);
        $this->openTransaction();
        $this->hasOpenTransaction()->shouldReturn(true);
    }
}
