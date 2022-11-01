<?php
require_once "./classes/Finance.php";

use classes\Finance;
use PHPUnit\Framework\TestCase;

class FinanceTest extends TestCase
{
    public function testGetAccounts()
    {
        $finance = new Finance();
        $accounts = $finance->getAccounts();
        $this->assertEquals(0, count($accounts));
    }

    public function testGetAccount()
    {
        $finance = new Finance();
        $accountNo = 1;
        $account = $finance->getAccount($accountNo);
        $this->assertEquals($accountNo, $account->getAccountNo());
    }

    public function testGetAccountNotFound()
    {
        $finance = new Finance();
        $accountNo = 10;
        $account = $finance->getAccount($accountNo);
        $this->assertEquals(null, $account);
    }

    public function testInitialBalanceAsZero()
    {
        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->getAccount(1);
        $this->assertEquals(0, $account->getBalance());
    }

    public function testInitialTransactionAsSingleArray()
    {
        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->getAccount(1);
        $this->assertEquals(1, count($account->getTransactions()));
    }

    public function testDepositNegativeAmount()
    {
        $this->expectExceptionMessage("Sorry, amount should be positive.");

        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->deposit(1, -10, "test", "test");
    }

    public function testDepositNoDate()
    {
        $this->expectExceptionMessage("Sorry, date is required.");

        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->deposit(1, 100, "");
    }

    public function testDepositInvalidDate()
    {
        $this->expectExceptionMessage("Sorry, date is invalid.");

        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->deposit(1, 100, "abc");
    }

    public function testDeposit()
    {
        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->deposit(1, 100, "2022-01-01");
        $this->assertEquals(100, $account->getBalance());
    }

    public function testDepositMultiple()
    {
        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->deposit(1, 100, "2022-01-01");
        $account = $finance->deposit(1, 50, "2022-01-02");
        $this->assertEquals(150, $account->getBalance());
    }

    public function testWithdrawalInsufficiantBalance()
    {
        $this->expectExceptionMessage("Sorry, Insufficiant Balance.");

        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->withdrawal(1, 100, "2022-01-01");
    }

    public function testWithdrawal()
    {
        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->deposit(1, 100, "2022-01-01");
        $account = $finance->withdrawal(1, 50, "2022-01-02");
        $this->assertEquals(50, $account->getBalance());
    }

    public function testWithdrawalBeforeDeposit()
    {
        $this->expectExceptionMessage("Sorry, Insufficiant Balance.");

        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->deposit(1, 100, "2022-01-02");
        $account = $finance->withdrawal(1, 50, "2022-01-01");
    }

    public function testDepositWithdrawalMultiple()
    {
        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->deposit(1, 100, "2022-01-01");
        $account = $finance->deposit(1, 200, "2022-01-02");
        $account = $finance->withdrawal(1, 50, "2022-01-03");
        $account = $finance->deposit(1, 500, "2022-01-04");
        $account = $finance->withdrawal(1, 300, "2022-01-05");
        $this->assertEquals(450, $account->getBalance());
    }

    public function testTransaction()
    {
        $finance = new Finance();
        $finance->createAccount(1, 0, "2022-01-01");
        $account = $finance->deposit(1, 100, "2022-01-01");
        $account = $finance->deposit(1, 150, "2022-01-02");
        $account = $finance->withdrawal(1, 200, "2022-01-03");
        $transactions = $account->getTransactions();
        $this->assertEquals(50, $account->getBalance());
        $this->assertEquals(100, $transactions[0]->getAmount());
        $this->assertEquals("deposit 100", $transactions[0]->getComment());
        $this->assertEquals("2022-01-01", $transactions[0]->getDate());
        $this->assertEquals(150, $transactions[1]->getAmount());
        $this->assertEquals("deposit 150", $transactions[1]->getComment());
        $this->assertEquals("2022-01-02", $transactions[1]->getDate());
        $this->assertEquals(-200, $transactions[2]->getAmount());
        $this->assertEquals("withdraw 200", $transactions[2]->getComment());
        $this->assertEquals("2022-01-03", $transactions[2]->getDate());
    }

    public function testTransferAccountToAccount()
    {
        $finance = new Finance();
        $account1 = $finance->getAccount(1);
        $account2 = $finance->getAccount(2);

        $account1 = $finance->deposit(1, 100, "2022-01-01");
        $account2 = $finance->deposit(1, 200, "2022-01-02");
        [$account1, $account2] = $finance->transfer(1, 2, 50, "2022-01-03");

        $this->assertEquals(50, $account1->getBalance());
        $this->assertEquals(250, $account2->getBalance());
    }

    public function testTransferAccountToAccountInsuficiantBalance()
    {
        $this->expectExceptionMessage("Sorry, Insufficiant Balance.");

        $finance = new Finance();
        $account1 = $finance->getAccount(1);
        $account2 = $finance->getAccount(2);

        $account1 = $finance->deposit(1, 100, "2022-01-01");
        $account2 = $finance->deposit(1, 200, "2022-01-02");
        [$account1, $account2] = $finance->transfer(1, 2, 150, "2022-01-03");
    }
}
