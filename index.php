<?php
require_once "./classes/Finance.php";

use classes\Finance;

try {
    $finance = Finance::getInstance();

    // create dummy accounts
    $finance->createAccount(1, 10, "2022-01-01");
    $finance->createAccount(2, 20, "2022-01-01");

    $accounts = $finance->getAccounts();
    echo "\n****** All Accounts ******";
    foreach ($accounts as $account) {
        echo "\nAccount No: " . $account->getAccountNo() . " | Balance: " . $account->getBalance();
    }

    $finance->deposit(1, 20, "2022-01-02");
    $finance->withdrawal(1, 15, "2022-01-03");
    $finance->transfer(1, 2, 10, "2022-01-04");

    $accounts = $finance->getAccounts();
    echo "\n\n****** All Accounts ******";
    foreach ($accounts as $account) {
        echo "\nAccount No: " . $account->getAccountNo() . " | Balance: " . $account->getBalance();
    }

    echo "\n\nAccount Balance: ";
    echo $finance->getAccountBalance(1);

    $account = $finance->getAccount(1);
    echo "\nAll Transaction sort by date for Account 1";
    foreach ($account->getTransactions("date") as $transaction) {
        echo "\n" . $transaction->getDate() . " | " . $transaction->getAmount() . " | " . $transaction->getComment();
    }

    echo "\n\nAccount Balance: ";
    echo $finance->getAccountBalance(2);
    $account = $finance->getAccount(2);
    echo "\nAll Transaction sort by date for Account 2";
    foreach ($account->getTransactions("date") as $transaction) {
        echo "\n" . $transaction->getDate() . " | " . $transaction->getAmount() . " | " . $transaction->getComment();
    }
    echo "\n\nAll Transaction sort by comment for Account 2";
    foreach ($account->getTransactions("comment") as $transaction) {
        echo "\n" . $transaction->getDate() . " | " . $transaction->getAmount() . " | " . $transaction->getComment();
    }

    echo "\n\n";
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage();
}
