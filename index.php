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
        echo "\nAccount No: " . $account->accountNo . " | Balance: " . $account->balance;
    }

    $finance->deposit(1, 20, "2022-01-02");
    $finance->withdrawal(1, 15, "2022-01-03");
    $finance->transfer(1, 2, 10, "2022-01-04");

    $accounts = $finance->getAccounts();
    echo "\n****** All Accounts ******";
    foreach ($accounts as $account) {
        echo "\nAccount No: " . $account->accountNo . " | Balance: " . $account->balance;
    }
    // // print all accounts
    // $finance->printAllAccounts();

    // // create account object
    // $account1 = $finance->getAccount(1);
    // $account2 = $finance->getAccount(2);

    // // print account1 balance
    // $finance->printAccountBalance($account1);

    // // transaction for account1
    // $account1 = $finance->deposit($account1, 100, "deposit 100", "2022-01-01");
    // $account1 = $finance->deposit($account1, 200, "deposit 200", "2022-01-02");
    // $account1 = $finance->withdrawal($account1, 50, "withdrawal 50", "2022-01-03");

    // // transaction for account2
    // $account2 = $finance->deposit($account2, 150, "deposit 150", "2022-01-01");

    // // transfer between account1 to account2
    // [$account1, $account2] = $finance->transfer($account1, $account2, 150, "2022-01-04");

    // // print account1 transaction
    // $finance->printAccountTransaction($account1);
    // $finance->printAccountTransaction($account1, ["sortBy" => "comment"]);

    // // print account2 transaction
    // $finance->printAccountTransaction($account2);

    // echo "\n\n";
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage();
}
