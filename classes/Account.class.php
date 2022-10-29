<?php

namespace classes;

require_once "./classes/Transaction.class.php";

use Exception;

class Account
{
    public function __construct($accountNo, $balance = 0, $transactions = [])
    {
        if (!$accountNo) throw new Exception("Sorry, accountNo is required.");
        if (!is_int($accountNo)) throw new Exception("Sorry, accountNo should be int.");
        if (!is_int($balance)) throw new Exception("Sorry, balance should be int.");
        if (!is_array($transactions)) throw new Exception("Sorry, transactions should be array.");

        $this->accountNo = $accountNo;
        $this->balance = $balance;
        $this->transactions = $transactions;
    }

    public function getAccountNo()
    {
        return $this->accountNo;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getTransactions()
    {
        return $this->transactions;
    }

    public function transaction($amount, $comment, $date)
    {
        $transaction = new Transaction($amount, $comment, $date);
        $transactions = $this->transactions;
        $transactions[] = $transaction;

        $balance = $this->checkBalanceFromTransaction($transactions);

        $this->transactions = $transactions;
        $this->balance = $balance;

        return $this;
    }

    private function checkBalanceFromTransaction($transactions)
    {
        // sort transaction based on date asc
        $date = array_column($transactions, "date");
        array_multisort($date, SORT_ASC, $transactions);

        $balance = 0;
        foreach ($transactions as $transaction) {
            $balance += $transaction->amount;
            if ($balance < 0) throw new Exception("Sorry, Insufficiant Balance.");
        }
        return $balance;
    }
}
