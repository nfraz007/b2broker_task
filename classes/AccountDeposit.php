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
        $this->account->transactions[] = $this->transaction;
        $this->account->balance += $this->transaction->amount;

        return $this->account;
    }
}
