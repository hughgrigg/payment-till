<?php

namespace Till\Io\Out;

use Till\Domain\Receipt;

abstract class ReceiptOutput
{
    /** @var Receipt */
    protected $receipt;

    /**
     * ReceiptPrint constructor.
     *
     * @param Receipt $receipt
     */
    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    /**
     * @return string
     */
    abstract public function print(): string;
}
