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

        $this->account->transactions[] = $this->transaction;
        $this->account->balance += $this->transaction->amount;

        $this->checkBalanceFromTransaction();

        return $this->account;
    }

    private function checkBalanceFromTransaction(): void
    {
        $transactions = $this->account->transactions;

        // sort transaction based on date asc
        $date = array_column($transactions, "date");
        array_multisort($date, SORT_ASC, $transactions);

        $balance = 0;
        foreach ($transactions as $transaction) {
            $balance += $transaction->amount;
            if ($balance < 0) throw new Exception("Sorry, Insufficiant Balance.");
        }
    }
}
