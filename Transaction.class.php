<?php

class Transaction
{
    public function __construct($amount, $comment, $date)
    {
        if (!$amount) throw new Exception("Sorry, amount is required.");
        if (!$comment) throw new Exception("Sorry, comment is required.");
        if (!$date) throw new Exception("Sorry, date is required.");
        if (!is_int($amount)) throw new Exception("Sorry, amount should be int.");
        if (!preg_match("/^[12][0-9]{3}-[01][0-9]-[0-3][0-9]$/", $date)) throw new Exception("Sorry, date is invalid");

        $this->amount = $amount;
        $this->comment = $comment;
        $this->date = $date;
    }
}
