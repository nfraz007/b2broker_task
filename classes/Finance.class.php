<?php

namespace classes;

require_once "./classes/Account.class.php";

use Exception;

class Finance
{
    private $accounts;

    public function __construct()
    {
        $this->accounts = [];
        $this->initAllAccount();
    }

    public function initAllAccount()
    {
        $this->createAccount(1);
        $this->createAccount(2);
    }

    public function createAccount($accountNo, $balance = 0, $transactions = [])
    {
        $account = $this->getAccount($accountNo);
        if ($account) throw new Exception("Sorry but accountNo $accountNo already exists.");

        $this->accounts[] = new Account($accountNo, $balance, $transactions);
    }

    /**
     * get all accounts in the system
     *
     * @return array
     */
    public function getAllAccounts()
    {
        return $this->accounts;
    }

    /**
     * get account by accountNo, else false
     *
     * @param int $accountNo
     * @return Account single account object
     */
    public function getAccount($accountNo)
    {
        foreach ($this->accounts as $account) {
            if ($account->accountNo == $accountNo) return $account;
        }
        return false;
    }

    /**
     * update account
     *
     * @param int $accountNo
     * @param Account $accountUpdated
     * @return void
     */
    public function updateAccount($accountNo, $accountUpdated)
    {
        foreach ($this->accounts as $index => $account) {
            if ($account->accountNo == $accountNo) $this->accounts[$index] = $accountUpdated;
        }
    }

    /**
     * deposit
     *
     * @param Account $account
     * @param int $amount
     * @param string $comment
     * @param string $date
     * @return Account
     */
    public function accountDeposit($account, $amount, $comment, $date)
    {
        if ($amount < 0) throw new Exception("Sorry, amount should be positive.");
        // echo "\n****** Deposit account " . $account->getAccountNo() . " | balance " . $account->getBalance() . " | amount $amount ******";

        $account = $account->transaction($amount, $comment, $date);
        $this->updateAccount($account->accountNo, $account);
        return $account;
    }

    /**
     * withdraw amount from account
     *
     * @param Account $account
     * @param int $amount
     * @param string $comment
     * @param string $date
     * @return Account
     */
    public function accountWithdrawal($account, $amount, $comment, $date)
    {
        if ($amount < 0) throw new Exception("Sorry, amount should be positive.");
        // echo "\n****** Withdrawal account " . $account->getAccountNo() . " | balance " . $account->getBalance() . " | amount $amount ******";

        $amount *= -1;
        $account = $account->transaction($amount, $comment, $date);
        $this->updateAccount($account->accountNo, $account);
        return $account;
    }

    /**
     * transfer from one account to another
     *
     * @param Account $accountFrom
     * @param Account $accountTo
     * @param int $amount
     * @param string $date
     * @return [Acount, Acount]
     */
    public function accountTransfer($accountFrom, $accountTo, $amount, $date)
    {
        if ($amount < 0) throw new Exception("Sorry, amount should be positive.");
        // echo "\n****** transfer from account " . $accountFrom->getAccountNo() . " to " . $accountTo->getAccountNo() . " | amount $amount ******";

        $accountFrom = $this->accountWithdrawal($accountFrom, $amount, "transfer to " . $accountFrom->getAccountNo() . ": $amount", $date);
        $accountTo = $this->accountDeposit($accountTo, $amount, "received from " . $accountTo->getAccountNo() . ": $amount", $date);
        return [$accountFrom, $accountTo];
    }

    /**
     * print all accounts
     *
     * @return void
     */
    public function printAllAccounts()
    {
        echo "\n****** All Accounts ******";
        foreach ($this->accounts as $account) {
            echo "\nAccount No: " . $account->accountNo . " | Balance: " . $account->balance;
        }
    }

    /**
     * print account balance
     *
     * @param Account $account
     * @return void
     */
    public function printAccountBalance($account)
    {
        $balance = $account->getBalance();
        echo "\n****** Account Balance for " . $account->getAccountNo() . " Balance: $balance ******";
    }

    /**
     * print account transaction
     *
     * @param Account $account
     * @return void
     */
    public function printAccountTransaction($account, $params = [])
    {
        $sortKey = $params && array_key_exists("sortBy", $params) && $params["sortBy"] && in_array($params["sortBy"], ["date", "comment"]) ? $params["sortBy"] : "date";

        echo "\n\n****** Account Transaction for " . $account->getAccountNo() . " sort by $sortKey ******";
        // sort transaction based on date asc
        $transactions = $account->getTransactions();
        $data = array_column($transactions, $sortKey);
        array_multisort($data, SORT_ASC, $transactions);

        foreach ($transactions as $transaction) {
            echo "\n";
            echo implode(" |  ", [$transaction->date, $transaction->amount, $transaction->comment]);
        }
        $this->printAccountBalance(($account));
    }
}
