<?php

namespace Till\Io\Out;

use Till\Domain\Purchase;

class TabularReceiptOutput extends ReceiptOutput
{
    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function print(): string
    {
        $table = new Table();

        $table->addRow('Item', 'Price');
        $this->receipt->purchases()->each(
            function (Purchase $purchase) use ($table) {
                $table->addRow(
                    $purchase->description(),
                    $purchase->amount()->formatted()
                );
            }
        );
        $table->addRow('');
        $table->addRow('Sub total', $this->receipt->subTotal()->formatted());
        $table->addRow(
            'Discounts',
            $this->receipt->totalDiscounts()->formatted()
        );
        $table->addRow('');
        $table->addRow(
            'Grand total',
            $this->receipt->grandTotal()->formatted()
        );

        return (string) $table;
    }
}
