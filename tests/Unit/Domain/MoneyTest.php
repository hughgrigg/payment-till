<?php

namespace Test\Unit\Domain;

use Money\Currency;
use PHPUnit\Framework\TestCase;
use Till\Domain\Money;

class MoneyTest extends TestCase
{
    /**
     * Test formatting of monetary amount.
     */
    public function testFormat()
    {
        $money = Money::fromAmount(2350);
        $this->assertEquals('£23.50', $money->formatted());
    }

    /**
     * Should be able to represent a negative monetary amount.
     */
    public function testNegative()
    {
        $money = Money::fromAmount(-5);
        $this->assertTrue($money->isNegative());
        $this->assertEquals('-£0.05', $money->formatted());
    }

    /**
     * Should be able to add monetary amounts.
     */
    public function testAdd()
    {
        $money = Money::fromAmount(150)->add(Money::fromAmount(200));
        $this->assertEquals(350, $money->amount());
        $this->assertEquals('£3.50', $money->formatted());
    }

    /**
     * Should be able to represent monetary amounts in different currencies.
     */
    public function testCurrency()
    {
        $gbp = new Money(new \Money\Money(100, new Currency('GBP')));
        $this->assertEquals('£1.00', $gbp->formatted());

        $eur = new Money(new \Money\Money(100, new Currency('EUR')));
        $this->assertEquals('€1.00', $eur->formatted());

        $usd = new Money(new \Money\Money(100, new Currency('USD')));
        $this->assertEquals('US$1.00', $usd->formatted());
    }
}
