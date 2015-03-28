<?php

namespace Isolate\PersistenceContext;

use Isolate\Exception\NotClosedTransactionException;
use Isolate\Exception\NotOpenedTransactionException;
use Isolate\PersistenceContext;
use Isolate\PersistenceContext\Transaction\Factory as TransactionFactory;

final class IsolateContext implements PersistenceContext
{
    /**
     * @var TransactionFactory
     */
    private $transactionFactory;

    /**
     * @var Transaction|null
     */
    private $transaction;

    /**
     * @param TransactionFactory $transactionFactory
     */
    public function __construct(TransactionFactory $transactionFactory)
    {
        $this->transactionFactory = $transactionFactory;
    }

    /**
     * @return Transaction
     * @throws NotClosedTransactionException
     */
    public function openTransaction()
    {
        if (!is_null($this->transaction)) {
            throw new NotClosedTransactionException();
        }

        $this->transaction = $this->transactionFactory->create();

        return $this->transaction;
    }

    /**
     * @return boolean
     */
    public function hasOpenTransaction()
    {
        return !is_null($this->transaction);
    }

    /**
     * @return Transaction
     * @throws NotOpenedTransactionException
     */
    public function getTransaction()
    {
        if (is_null($this->transaction)) {
            throw new NotOpenedTransactionException();
        }

        return $this->transaction;
    }

    /**
     * @throws NotOpenedTransactionException
     */
    public function closeTransaction()
    {
        if (is_null($this->transaction)) {
            throw new NotOpenedTransactionException();
        }

        $this->transaction->commit();

        unset($this->transaction);
    }
}
