<?php

namespace Isolate\PersistenceContext;

use Isolate\Exception\NotClosedTransactionException;
use Isolate\Exception\NotOpenedTransactionException;
use Isolate\PersistenceContext;
use Isolate\PersistenceContext\Transaction\Factory as TransactionFactory;

final class IsolateContext implements PersistenceContext
{
    /**
     * @var Name
     */
    private $name;

    /**
     * @var TransactionFactory
     */
    private $transactionFactory;

    /**
     * @var Transaction|null
     */
    private $transaction;

    /**
     * @param Name $name
     * @param TransactionFactory $transactionFactory
     */
    public function __construct(Name $name, TransactionFactory $transactionFactory)
    {
        $this->transactionFactory = $transactionFactory;
        $this->name = $name;
    }

    /**
     * @return Name
     */
    public function getName()
    {
        return $this->name;
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

        $this->transaction = $this->transactionFactory->create($this);

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
