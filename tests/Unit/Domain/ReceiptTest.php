<?php

namespace Test\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Till\Domain\Discount;
use Till\Domain\Money;
use Till\Domain\Purchase;
use Till\Domain\Receipt;

class ReceiptTest extends TestCase
{
    /**
     * Lines should contain purchases and discounts.
     */
    public function testLines()
    {
        $purchase = new Purchase('foo', Money::fromAmount(50));
        $discount = new Discount('bar', Money::fromAmount(-25));

        $receipt = new Receipt();
        $receipt->addPurchase($purchase);
        $receipt->addDiscount($discount);

        $this->assertContains($purchase, $receipt->lines());
        $this->assertContains($discount, $receipt->lines());
    }

    /**
     * Sub-total should contain purchases only.
     */
    public function testSubTotal()
    {
        $purchaseA = new Purchase('foo', Money::fromAmount(50));
        $purchaseB = new Purchase('foo', Money::fromAmount(100));
        $discount = new Discount('bar', Money::fromAmount(-25));

        $receipt = new Receipt();
        $receipt->addPurchase($purchaseA);
        $receipt->addPurchase($purchaseB);
        $receipt->addDiscount($discount);

        $this->assertEquals(150, $receipt->subTotal()->amount());
    }

    /**
     * Total discounts should contain discounts only.
     */
    public function testTotalDiscounts()
    {
        $purchase = new Purchase('foo', Money::fromAmount(50));
        $discountA = new Discount('bar', Money::fromAmount(-35));
        $discountB = new Discount('bar', Money::fromAmount(-15));

        $receipt = new Receipt();
        $receipt->addPurchase($purchase);
        $receipt->addDiscount($discountA);
        $receipt->addDiscount($discountB);

        $this->assertEquals(-50, $receipt->totalDiscounts()->amount());
    }

    /**
     * Grand total should contain purchases and discounts.
     */
    public function testGrandTotal()
    {
        $purchase = new Purchase('foo', Money::fromAmount(50));
        $discount = new Discount('bar', Money::fromAmount(-25));

        $receipt = new Receipt();
        $receipt->addPurchase($purchase);
        $receipt->addDiscount($discount);

        $this->assertEquals(25, $receipt->grandTotal()->amount());
    }
}
