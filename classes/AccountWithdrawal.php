<?php

namespace classes;

use entity\AccountInterface;
use entity\TransactionInterface;
use Exception;

class AccountWithdrawal implements AccountTransactionInterface
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

        $this->checkBalanceFromTransaction();

        return $this->account;
    }

    private function checkBalanceFromTransaction(): void
    {
        $transactions = $this->account->getTransactions("date");

        $balance = 0;
        foreach ($transactions as $transaction) {
            $balance += $transaction->getAmount();
            if ($balance < 0) throw new Exception("Sorry, Insufficiant Balance.");
        }
    }
}
