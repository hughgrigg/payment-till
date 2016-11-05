<?php

namespace Till\Domain;
use Money\Currency;

/**
 * A single line purchase on the receipt.
 */
abstract class Line
{
    /** @var string */
    private $description;

    /** @var Money */
    private $amount;

    /**
     * @param string $description
     * @param Money  $amount
     */
    public function __construct(string $description, Money $amount)
    {
        $this->description = $description;
        $this->amount = $amount;
    }

    /**
     * @param Receipt $receipt
     *
     * @return void
     */
    abstract public function addToReceipt(Receipt $receipt);

    /**
     * Factory method for lines.
     *
     * @param string $description
     * @param Money  $amount
     *
     * @return Line
     */
    public static function make(string $description, Money $amount): Line
    {
        if ($amount->isNegative()) {
            return new Discount($description, $amount);
        }

        return new Purchase($description, $amount);
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return Money
     */
    public function amount(): Money
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function currency(): Currency
    {
        return $this->amount->currency();
    }
}
