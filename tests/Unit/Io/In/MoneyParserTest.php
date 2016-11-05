<?php

namespace Test\Unit\Io\In;

use Money\Currencies\ISOCurrencies;
use PHPUnit\Framework\TestCase;
use Till\Io\In\MoneyParser;

class MoneyParserTest extends TestCase
{
    /** @var MoneyParser */
    private $moneyParser;

    /**
     * Set up money parser for each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->moneyParser = new MoneyParser(new ISOCurrencies());
    }

    /**
     * Should be able to parse without a currency symbol.
     */
    public function testDefaultCurrency()
    {
        $money = $this->moneyParser->parse('15');
        $this->assertEquals('£15.00', $money->formatted());
    }

    /**
     * Should be able to parse different currency symbols.
     */
    public function testParseCurrency()
    {
        $gbp = $this->moneyParser->parse('£8.05');
        $this->assertEquals(805, $gbp->amount());
        $this->assertEquals('£8.05', $gbp->formatted());

        $eur = $this->moneyParser->parse('€5');
        $this->assertEquals(500, $eur->amount());
        $this->assertEquals('€5.00', $eur->formatted());

        $usd = $this->moneyParser->parse('$0.01');
        $this->assertEquals(1, $usd->amount());
        $this->assertEquals('US$0.01', $usd->formatted());
    }

    /**
     * Should be able to parse a negative monetary amount. Note that the sign
     * must come after the currency symbol for this parser, but the formatted
     * amount will place it before the currency symbol.
     */
    public function testParseNegative()
    {
        $negative = $this->moneyParser->parse('£-10');
        $this->assertEquals(-1000, $negative->amount());
        $this->assertEquals('-£10.00', $negative->formatted());
    }
}
