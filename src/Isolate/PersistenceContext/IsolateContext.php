<?php

namespace Isolate\PersistenceContext;

use Isolate\Exception\NotClosedTransactionException;
use Isolate\Exception\NotOpenedTransactionException;
use Isolate\PersistenceContext;
use Isolate\PersistenceContext\Transaction\Factory as TransactionFactory;
use Isolate\UnitOfWork\Factory as UOWFactory;

final class IsolateContext implements PersistenceContext
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var TransactionFactory
     */
    private $transactionFactory;

    /**
     * @var UOWFactory
     */
    private $unitOfWork;

    /**
     * @var Transaction|null
     */
    private $transaction;

    /**
     * @param string $name
     * @param UOWFactory $unitOfWorkFactory
     * @param TransactionFactory $transactionFactory
     */
    public function __construct($name, UOWFactory $unitOfWorkFactory, TransactionFactory $transactionFactory)
    {
        $this->name = $name;
        $this->transactionFactory = $transactionFactory;
        $this->unitOfWork = $unitOfWorkFactory->create();
    }

    /**
     * @return string
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

        $this->transaction = $this->transactionFactory->create($this->unitOfWork);

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
