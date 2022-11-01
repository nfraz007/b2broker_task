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

### Singleton Design Pattern

- To avoid multiple object creation, a singleton design pattern is used
- In our case, I have used `Finance` class as a singleton class.
- This class is responsible to store all `accounts` data, and if we will initiate this class then we have to again put all the dummy data.
- This class also have some important function like `createAccount`, `getAccounts`, `getAccount`, `getAccountBalance`, `deposit`, `withdrawal`, and `transfer`

### Factory Design Pattern

- to get more control over object creation, a factory class is created which is responsible to create other class objects.
- In our case, I created two operations `AccountDeposit` and `AccountWithdrawal` as a separate class, which has their own logic to perform
- I added a factory class `TransactionFactory` which is creating objects based on `deposit` or `withdrawal`

### Dependencies Injection Design Pattern

- to get more control over object creation, we can pass each dependency to the class constructor.
- I have used this pattern in multiple places like `AccountDeposit` and `AccountWithdrawal` where I am passing dependencies like `Account` and `Transaction` objects in the constructor

### Entity

#### Account

- it has three properties `accountNo`, `balance`, and `transactions`
- `accountNo` stores the account number
- `balance` stores the current balance of the `account`
- `transactions` stores the array of `Transaction`

#### Transaction

- it has three properties `amount`, `comment`, and `date`
- `amount` will be positive if deposit, else negative for withdrawal
- `date` will be in `2020-01-01` format

```sh
php index.php
```

```txt
****** All Accounts ******
Account No: 1 | Balance: 10
Account No: 2 | Balance: 20

****** All Accounts ******
Account No: 1 | Balance: 5
Account No: 2 | Balance: 30

Account Balance: 5
All Transaction sort by date for Account 1
2022-01-01 | 10 | Initial Balance
2022-01-02 | 20 | deposit 20
2022-01-03 | -15 | withdraw 15
2022-01-04 | -10 | transfer to 1: 10

Account Balance: 30
All Transaction sort by date for Account 2
2022-01-01 | 20 | Initial Balance
2022-01-04 | 10 | received from 2: 10

All Transaction sort by comment for Account 2
2022-01-01 | 20 | Initial Balance
2022-01-04 | 10 | received from 2: 10
```

## PHPStan

I have added phpstan code validator with level 9 in `phpstan.neon` file.

```sh
vendor/bin/phpstan analyse entity classes
```

```txt
Note: Using configuration file /Applications/XAMPP/xamppfiles/htdocs/personal/b2broker_task/phpstan.neon.
 6/6 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

 [OK] No errors

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
 ✔ Get accounts
 ✔ Get account
 ✔ Get account not found
 ✔ Initial balance as zero
 ✔ Initial transaction as single array
 ✔ Deposit negative amount
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

Time: 00:00.020, Memory: 6.00 MB

```
