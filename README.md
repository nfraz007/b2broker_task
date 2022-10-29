# Task Question:

Implement a set of classes for managing the financial operations of an account.
There are three types of transactions: deposits, withdrawals, and transfers from account to account.
The transaction contains a comment, an amount, and a due date.

Required methods:

- get all accounts in the system.
- get the balance of a specific account
- perform an operation
- get all account transactions sorted by a comment in alphabetical order.
- get all account transactions sorted by date.

The test task must be implemented without the use of frameworks and databases. This is necessary in order to see your coding style, ability to understand and implement the task and demonstrate your skills.

## Design Pattern

I have used Object Oriented Programming (OOP) to solve this problem. I have broken this problem into three small entities `Account`, `Transaction`, and `Finance`

#### Account

- it has three properties `accountNo`, `balance`, and `transactions`
- `accountNo` stores the account number
- `balance` stores the current balance of the `account`
- `transactions` stores the array of `Transaction`
- it also has a `transaction()` method, which creates a new `Transaction` object and inserts it in the `transactions` array. It also updates the `balance`

#### Transaction

- it has three properties `amount`, `comment`, and `date`
- `amount` will be positive if deposit, else negative for withdrawal
- `date` will be in `2020-01-01` format

#### Finance

- it has all the core business logic and we only have to import this class
- when we create an object, then it initiated all the dummy accounts like `accountNo` 1 and 2
- `getAllAccounts()` function can be used to get all accounts in the system
- `getAccount()` function can be used to get a single account
- `accountDeposit()` will be used for the deposit
- `accountWithdrawal()` will be used for withdrawal
- `accountTransfer()` will be used for transfer from one account to another

I have added a simple working example in `index.php` file. to run this use this command

```sh
php index.php
```

```txt
****** All Accounts ******
Account No: 1 | Balance: 0
Account No: 2 | Balance: 0
****** Account Balance for 1 Balance: 0 ******

****** Account Transaction for 1 sort by date ******
2022-01-01 |  100 |  deposit 100
2022-01-02 |  200 |  deposit 200
2022-01-03 |  -50 |  withdrawal 50
2022-01-04 |  -150 |  transfer to 1: 150
****** Account Balance for 1 Balance: 100 ******

****** Account Transaction for 1 sort by comment ******
2022-01-01 |  100 |  deposit 100
2022-01-02 |  200 |  deposit 200
2022-01-04 |  -150 |  transfer to 1: 150
2022-01-03 |  -50 |  withdrawal 50
****** Account Balance for 1 Balance: 100 ******

****** Account Transaction for 2 sort by date ******
2022-01-01 |  150 |  deposit 150
2022-01-04 |  150 |  received from 2: 150
****** Account Balance for 2 Balance: 300 ******
```

## Unit Test

It also have PHPUnit to test each functionality and corner case. To run PHPUnit, follow these steps

```sh
composer install
./vendor/bin/phpunit --testdox
```

```txt
PHPUnit 9.5.26 by Sebastian Bergmann and contributors.

Finance
 ✔ Get all accounts
 ✔ Get account
 ✔ Get account not found
 ✔ Initial balance as zero
 ✔ Initial transaction as blank array
 ✔ Deposit invalid amount
 ✔ Deposit negative amount
 ✔ Deposit zero amount
 ✔ Deposit no comment
 ✔ Deposit no date
 ✔ Deposit invalid date
 ✔ Deposit
 ✔ Deposit multiple
 ✔ Withdrawal insufficiant balance
 ✔ Withdrawal
 ✔ Withdrawal before deposit
 ✔ Deposit withdrawal multiple
 ✔ Transaction
 ✔ Transfer account to account
 ✔ Transfer account to account insuficiant balance

Time: 00:00.018, Memory: 6.00 MB

OK (20 tests, 30 assertions)
```
