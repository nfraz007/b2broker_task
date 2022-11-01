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
}

class Account implements AccountInterface
{
    public int $accountNo;
    public int $balance;
    /** @var array<TransactionInterface> $transactions */
    public array $transactions;

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
}
