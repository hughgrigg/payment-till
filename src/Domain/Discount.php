<?php

namespace Till\Domain;

/**
 * A discount on the receipt.
 */
class Discount extends Line
{
    /**
     * @param Receipt $receipt
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addToReceipt(Receipt $receipt)
    {
        $receipt->addDiscount($this);
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return 'Discount: '.parent::description();
    }
}
