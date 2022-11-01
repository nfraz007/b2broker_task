<?php

namespace classes;

require_once __DIR__ . "/../entity/Account.php";
require_once __DIR__ . "/TransactionFactory.php";

use Exception;
use entity\Account;
use entity\Transaction;
use classes\TransactionFactory;
use entity\AccountInterface;

interface FinanceInterface
{
    public static function getInstance(): FinanceInterface;
    public function createAccount(int $accountNo, int $balance, string $date): void;
    /** @return array<AccountInterface> */
    public function getAccounts(): array;
    public function getAccount(int $accountNo): ?AccountInterface;
    public function updateAccount(int $accountNo, AccountInterface $accountUpdated): void;
    public function deposit(int $accountNo, int $amount, string $date, string $comment = null): ?AccountInterface;
    public function withdrawal(int $accountNo, int $amount, string $date, string $comment = null): ?AccountInterface;
    /** @return array<AccountInterface> */
    public function transfer(int $accountNoFrom, int $accountNoTo, int $amount, string $date): array;
}

class Finance implements FinanceInterface
{
    private static FinanceInterface $instance;
    /** @var array<AccountInterface> */
    private array $accounts;

    public function __construct()
    {
        $this->accounts = [];
    }

    public static function getInstance(): FinanceInterface
    {
        if (!isset(self::$instance)) {
            self::$instance = new Finance();
        }
        return self::$instance;
    }

    public function createAccount(int $accountNo, int $balance, string $date): void
    {
        $account = $this->getAccount($accountNo);
        if ($account) throw new Exception("Sorry but accountNo $accountNo already exists.");

        $account = new Account($accountNo, 0);
        $transaction = new Transaction($balance, $date, "Initial Balance");
        $accountTransaction = TransactionFactory::create("deposit", $account, $transaction);
        $account = $accountTransaction->makeTransaction();

        $this->accounts[] = $account;
    }

    public function getAccounts(): array
    {
        return $this->accounts;
    }

    public function getAccount(int $accountNo): ?AccountInterface
    {
        foreach ($this->accounts as $account) {
            if ($account->getAccountNo() == $accountNo) return $account;
        }
        return null;
    }

    public function updateAccount(int $accountNo, AccountInterface $accountUpdated): void
    {
        foreach ($this->accounts as $index => $account) {
            if ($account->getAccountNo() == $accountNo) $this->accounts[$index] = $accountUpdated;
        }
    }

    public function deposit(int $accountNo, int $amount, string $date, string $comment = null): ?AccountInterface
    {
        if ($amount < 0) throw new Exception("Sorry, amount should be positive.");

        $account = $this->getAccount($accountNo);
        if ($account) {
            $transaction = new Transaction($amount, $date, $comment);
            $accountTransaction = TransactionFactory::create("deposit", $account, $transaction);
            $account = $accountTransaction->makeTransaction();
            $this->updateAccount($account->getAccountNo(), $account);
        }
        return $account;
    }

    public function withdrawal(int $accountNo, int $amount, string $date, string $comment = null): ?AccountInterface
    {
        if ($amount < 0) throw new Exception("Sorry, amount should be positive.");

        $account = $this->getAccount($accountNo);
        if ($account) {
            $transaction = new Transaction($amount * -1, $date, $comment);
            $accountTransaction = TransactionFactory::create("withdrawal", $account, $transaction);
            $account = $accountTransaction->makeTransaction();
            $this->updateAccount($account->getAccountNo(), $account);
        }

        return $account;
    }

    /** @return array<AccountInterface> */
    public function transfer(int $accountNoFrom, int $accountNoTo, int $amount, string $date): array
    {
        if ($amount < 0) throw new Exception("Sorry, amount should be positive.");

        $accountFrom = $this->getAccount($accountNoFrom);
        $accountTo = $this->getAccount($accountNoTo);

        if ($accountFrom) {
            $comment = "transfer to " . $accountFrom->getAccountNo() . ": $amount";
            $accountFrom = $this->withdrawal($accountNoFrom, $amount, $date, $comment);
        }
        if ($accountTo) {
            $comment = "received from " . $accountTo->getAccountNo() . ": $amount";
            $accountTo = $this->deposit($accountNoTo, $amount, $date, $comment);
        }
        return [$accountFrom, $accountTo];
    }
}
