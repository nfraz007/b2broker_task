<?php

namespace classes;

use entity\AccountInterface;
use entity\TransactionInterface;

interface AccountTransactionInterface
{
    public function makeTransaction(): AccountInterface;
}

class AccountDeposit implements AccountTransactionInterface
{
    private AccountInterface $account;
    private TransactionInterface $transaction;

    public function __construct(AccountInterface $account, TransactionInterface $transaction)
    {
        $this->account = $account;
        $this->transaction = $transaction;
    }

    public function makeTransaction(): AccountInterface
    {
        $this->account->appendTransactions($this->transaction);
        $this->account->increaseBalance($this->transaction->getAmount());

        return $this->account;
    }
}
