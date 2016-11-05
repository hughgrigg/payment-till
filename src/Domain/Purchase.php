<?php

namespace Till\Domain;

/**
 * A single line purchase on the receipt.
 */
class Purchase extends Line
{
    /**
     * @param Receipt $receipt
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addToReceipt(Receipt $receipt)
    {
        $receipt->addPurchase($this);
    }
}
