<?php

namespace Till\Domain;

use Money\Currency;
use Till\Io\Out\FormatsMoney;

/**
 * A monetary amount. Wrapper for PHP Money.
 */
class Money
{
    use FormatsMoney;

    /** @var \Money\Money */
    private $money;

    /**
     * @param \Money\Money $money
     */
    public function __construct(\Money\Money $money)
    {
        $this->money = $money;
    }

    /**
     * @param int    $amount
     * @param string $currency
     *
     * @return Money
     * @throws \InvalidArgumentException
     */
    public static function fromAmount(int $amount, string $currency = 'GBP')
    {
        return new self(new \Money\Money($amount, new Currency($currency)));
    }

    /**
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->money->isNegative();
    }

    /**
     * @return int
     */
    public function amount(): int
    {
        return $this->money->getAmount();
    }

    /**
     * @return string
     */
    public function formatted(): string
    {
        return $this->formatMoney($this);
    }

    /**
     * @return Currency
     */
    public function currency(): Currency
    {
        return $this->money->getCurrency();
    }

    /**
     * @param Money $money
     *
     * @return Money
     * @throws \InvalidArgumentException
     */
    public function add(Money $money): Money
    {
        return new self($this->money->add($money->wrapped()));
    }

    /**
     * @return \Money\Money
     */
    public function wrapped(): \Money\Money
    {
        return $this->money;
    }
}
