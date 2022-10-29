<?php
require_once "./classes/Finance.class.php";

use classes\Finance;

try {
    $finance = new Finance();

    // print all accounts
    $finance->printAllAccounts();

    // create account object
    $account1 = $finance->getAccount(1);
    $account2 = $finance->getAccount(2);

    // print account1 balance
    $finance->printAccountBalance($account1);

    // transaction for account1
    $account1 = $finance->accountDeposit($account1, 100, "deposit 100", "2022-01-01");
    $account1 = $finance->accountDeposit($account1, 200, "deposit 200", "2022-01-02");
    $account1 = $finance->accountWithdrawal($account1, 50, "withdrawal 50", "2022-01-03");

    // transaction for account2
    $account2 = $finance->accountDeposit($account2, 150, "deposit 150", "2022-01-01");

    // transfer between account1 to account2
    [$account1, $account2] = $finance->accountTransfer($account1, $account2, 150, "2022-01-04");

    // print account1 transaction
    $finance->printAccountTransaction($account1);
    // $finance->printAccountTransaction($account1, ["sortBy" => "comment"]);

    // print account2 transaction
    $finance->printAccountTransaction($account2);

    echo "\n\n";
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage();
}
