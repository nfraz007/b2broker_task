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
    public function getAmount(): int;
    public function setAmount(int $amount): void;
    public function getDate(): string;
    public function setDate(string $date): void;
    public function getComment(): string;
    public function setComment(string $comment): void;
}
class Transaction implements TransactionInterface
{
    private int $amount;
    private string $date;
    private string $comment;

    public function __construct(int $amount, string $date, string $comment = null)
    {
        if (!$date) throw new Exception("Sorry, date is required.");
        if (!is_int($amount)) throw new Exception("Sorry, amount should be int.");
        if (!preg_match("/^[12][0-9]{3}-[01][0-9]-[0-3][0-9]$/", $date)) throw new Exception("Sorry, date is invalid.");

        if (!$comment) {
            $comment = $amount < 0 ? "withdraw " . abs($amount) : "deposit " . $amount;
        }
        $this->setAmount($amount);
        $this->setDate($date);
        $this->setComment($comment);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}
