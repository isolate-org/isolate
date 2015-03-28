<?php

namespace spec\Isolate\PersistenceContext;

use Isolate\Exception\NotClosedTransactionException;
use Isolate\Exception\NotOpenedTransactionException;
use Isolate\PersistenceContext\Transaction;
use Isolate\PersistenceContext\Transaction\Factory as TransactionFactory;
use Isolate\UnitOfWork\Factory;
use Isolate\UnitOfWork\UnitOfWork;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IsolateContextSpec extends ObjectBehavior
{
    const NAME = 'context';

    function let(Factory $factory, UnitOfWork $uow, TransactionFactory $transactionFactory)
    {
        $factory->create()->willReturn($uow);

        $this->beConstructedWith(self::NAME, $factory, $transactionFactory);
    }

    function it_is_named()
    {
        $this->getName()->shouldReturn(self::NAME);
    }

    function it_open_transactions(TransactionFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create(Argument::type('Isolate\UnitOfWork\UnitOfWork'))
            ->willReturn($transaction);

        $this->openTransaction()->shouldReturn($transaction);
    }

    function it_throws_exception_when_opening_transaction_before_closing_old(TransactionFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create(Argument::type('Isolate\UnitOfWork\UnitOfWork'))
            ->willReturn($transaction);

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
        $transactionFactory->create(Argument::type('Isolate\UnitOfWork\UnitOfWork'))
            ->willReturn($transaction);

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
        $transactionFactory->create(Argument::type('Isolate\UnitOfWork\UnitOfWork'))
            ->willReturn($transaction);

        $this->openTransaction();

        $this->getTransaction()->shouldReturn($transaction);
    }

    function it_knows_whenever_transaction_is_open_or_not(TransactionFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create(Argument::type('Isolate\UnitOfWork\UnitOfWork'))
            ->willReturn($transaction);

        $this->hasOpenTransaction()->shouldReturn(false);
        $this->openTransaction();
        $this->hasOpenTransaction()->shouldReturn(true);
    }
}
