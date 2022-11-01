<?php
require_once "./classes/Finance.class.php";

use classes\Finance;
use PHPUnit\Framework\TestCase;

class FinanceTest extends TestCase
{
    public function testGetAllAccounts()
    {
        $finance = new Finance();
        $accounts = $finance->getAllAccounts();
        $this->assertEquals(2, count($accounts));
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
        $account = $finance->getAccount(1);
        $this->assertEquals(0, $account->getBalance());
    }

    public function testInitialTransactionAsBlankArray()
    {
        $finance = new Finance();
        $account = $finance->getAccount(1);
        $this->assertEquals(0, count($account->getTransactions()));
    }

    public function testDepositInvalidAmount()
    {
        $this->expectExceptionMessage("Sorry, amount should be int.");

        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, "abc", "test", "test");
    }

    public function testDepositNegativeAmount()
    {
        $this->expectExceptionMessage("Sorry, amount should be positive.");

        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, -10, "test", "test");
    }

    public function testDepositZeroAmount()
    {
        $this->expectExceptionMessage("Sorry, amount is required.");

        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 0, "test", "test");
    }

    public function testDepositNoComment()
    {
        $this->expectExceptionMessage("Sorry, comment is required.");

        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 100, "", "test");
    }

    public function testDepositNoDate()
    {
        $this->expectExceptionMessage("Sorry, date is required.");

        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 100, "deposit 100", "");
    }

    public function testDepositInvalidDate()
    {
        $this->expectExceptionMessage("Sorry, date is invalid.");

        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 100, "deposit 100", "abc");
    }

    public function testDeposit()
    {
        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 100, "deposit 100", "2022-01-01");
        $this->assertEquals(100, $account->getBalance());
    }

    public function testDepositMultiple()
    {
        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 100, "deposit 100", "2022-01-01");
        $account = $finance->deposit($account, 50, "deposit 50", "2022-01-02");
        $this->assertEquals(150, $account->getBalance());
    }

    public function testWithdrawalInsufficiantBalance()
    {
        $this->expectExceptionMessage("Sorry, Insufficiant Balance.");

        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->withdrawal($account, 100, "withdraw 100", "2022-01-01");
    }

    public function testWithdrawal()
    {
        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 100, "deposit 100", "2022-01-01");
        $account = $finance->withdrawal($account, 50, "withdraw 50", "2022-01-02");
        $this->assertEquals(50, $account->getBalance());
    }

    public function testWithdrawalBeforeDeposit()
    {
        $this->expectExceptionMessage("Sorry, Insufficiant Balance.");

        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 100, "deposit 100", "2022-01-02");
        $account = $finance->withdrawal($account, 50, "withdraw 50", "2022-01-01");
    }

    public function testDepositWithdrawalMultiple()
    {
        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 100, "deposit 100", "2022-01-01");
        $account = $finance->deposit($account, 200, "deposit 200", "2022-01-02");
        $account = $finance->withdrawal($account, 50, "withdraw 50", "2022-01-03");
        $account = $finance->deposit($account, 500, "deposit 500", "2022-01-04");
        $account = $finance->withdrawal($account, 300, "withdraw 300", "2022-01-05");
        $this->assertEquals(450, $account->getBalance());
    }

    public function testTransaction()
    {
        $finance = new Finance();
        $account = $finance->getAccount(1);
        $account = $finance->deposit($account, 100, "deposit 100", "2022-01-01");
        $account = $finance->deposit($account, 150, "deposit 150", "2022-01-02");
        $account = $finance->withdrawal($account, 200, "withdraw 200", "2022-01-03");
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

        $account1 = $finance->deposit($account1, 100, "deposit 100", "2022-01-01");
        $account2 = $finance->deposit($account2, 200, "deposit 200", "2022-01-02");
        [$account1, $account2] = $finance->transfer($account1, $account2, 50, "2022-01-03");

        $this->assertEquals(50, $account1->getBalance());
        $this->assertEquals(250, $account2->getBalance());
    }

    public function testTransferAccountToAccountInsuficiantBalance()
    {
        $this->expectExceptionMessage("Sorry, Insufficiant Balance.");

        $finance = new Finance();
        $account1 = $finance->getAccount(1);
        $account2 = $finance->getAccount(2);

        $account1 = $finance->deposit($account1, 100, "deposit 100", "2022-01-01");
        $account2 = $finance->deposit($account2, 200, "deposit 200", "2022-01-02");
        [$account1, $account2] = $finance->transfer($account1, $account2, 150, "2022-01-03");
    }
}
