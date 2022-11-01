<?php

namespace entity;

use Exception;

/**
 * @property int $amount
 * @property string $date
 * @property string $comment
 */
interface TransactionInterface
{
}
class Transaction implements TransactionInterface
{
    public int $amount;
    public string $date;
    public string $comment;

    public function __construct(int $amount, string $date, string $comment = null)
    {
        if (!$date) throw new Exception("Sorry, date is required.");
        if (!is_int($amount)) throw new Exception("Sorry, amount should be int.");
        if (!preg_match("/^[12][0-9]{3}-[01][0-9]-[0-3][0-9]$/", $date)) throw new Exception("Sorry, date is invalid.");

        if (!$comment) {
            $comment = $amount < 0 ? "withdrawal " . abs($amount) : "deposit " . $amount;
        }
        $this->amount = $amount;
        $this->comment = $comment;
        $this->date = $date;
    }
}
