<?php

namespace classes;

require_once __DIR__ . "/AccountDeposit.php";
require_once __DIR__ . "/AccountWithdrawal.php";

use entity\AccountInterface;
use entity\TransactionInterface;

interface TransactionFactoryInterface
{
    public static function create(string $type, AccountInterface $account, TransactionInterface $transaction): AccountTransactionInterface;
}

class TransactionFactory implements TransactionFactoryInterface
{
    public static function create(string $type, AccountInterface $account, TransactionInterface $transaction): AccountTransactionInterface
    {
        return $type == "deposit" ? new AccountDeposit($account, $transaction) : new AccountWithdrawal($account, $transaction);
    }
}
