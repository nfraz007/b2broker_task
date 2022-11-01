<?php

namespace entity;

require_once __DIR__ . "/Transaction.php";

use Exception;

/**
 * @property int $accountNo
 * @property int $balance
 * @property array<TransactionInterface> $transactions
 */
interface AccountInterface
{
    public function getAccountNo(): int;
    public function setAccountNo(int $accountNo): void;
    public function getBalance(): int;
    public function setBalance(int $balance): void;
    public function increaseBalance(int $amount): void;
    /** @return array<TransactionInterface> */
    public function getTransactions(string $sortBy = null): array;
    /** @param array<TransactionInterface> $transactions */
    public function setTransactions(array $transactions): void;
    public function appendTransactions(TransactionInterface $transaction): void;
}

class Account implements AccountInterface
{
    private int $accountNo;
    private int $balance;
    /** @var array<TransactionInterface> $transactions */
    private array $transactions;

    /** @param array<TransactionInterface> $transactions */
    public function __construct(int $accountNo, int $balance = 0, array $transactions = [])
    {
        if (!$accountNo) throw new Exception("Sorry, accountNo is required.");
        if (!is_int($accountNo)) throw new Exception("Sorry, accountNo should be int.");
        if (!is_int($balance)) throw new Exception("Sorry, balance should be int.");
        if (!is_array($transactions)) throw new Exception("Sorry, transactions should be array.");

        $this->accountNo = $accountNo;
        $this->balance = $balance;
        $this->transactions = $transactions;
    }

    public function getAccountNo(): int
    {
        return $this->accountNo;
    }

    public function setAccountNo(int $accountNo): void
    {
        $this->accountNo = $accountNo;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function increaseBalance(int $amount): void
    {
        $this->balance += $amount;
    }

    /** @return array<TransactionInterface> */
    public function getTransactions(string $sortBy = null): array
    {
        $transactions = $this->transactions;

        if ($sortBy == "date" || $sortBy == "comment") {
            $transactions = array_map(function ($v) {
                return [
                    "amount" => $v->getAmount(),
                    "date" => $v->getDate(),
                    "comment" => $v->getComment(),
                ];
            }, $this->transactions);
            $sortByKey = $sortBy == "comment" ? "comment" : "date";
            $sortByCol = array_column($transactions, $sortByKey);
            array_multisort($sortByCol, SORT_ASC, $transactions);
            $transactions = array_map(function ($v) {
                return new Transaction($v["amount"], $v["date"], $v["comment"]);
            }, $transactions);
        }
        return $transactions;
    }

    /** @param array<TransactionInterface> $transactions */
    public function setTransactions(array $transactions): void
    {
        $this->transactions = $transactions;
    }

    public function appendTransactions(TransactionInterface $transaction): void
    {
        $this->transactions[] = $transaction;
    }
}
