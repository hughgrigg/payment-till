<?php

namespace Till\Io\In;

use InvalidArgumentException;
use Money\Currencies;
use Money\Currency;
use Till\Domain\Money;

/**
 * Make money instances from strings.
 */
class MoneyParser
{
    /** @var array */
    private static $symbolMap = [
        '£' => 'GBP',
        '€' => 'EUR',
        '$' => 'USD',
    ];

    /** @var Currencies */
    private $currencies;

    /**
     * @param Currencies $currencies
     */
    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;
    }

    /**
     * @param string $monetary
     *
     * @return Money
     * @throws \Money\Exception\UnknownCurrencyException
     * @throws \InvalidArgumentException
     */
    public function parse(string $monetary): Money
    {
        if (is_numeric($monetary)) {
            return $this->numericToMoney($monetary);
        }

        $symbol = mb_substr($monetary, 0, 1);
        if (array_key_exists($symbol, self::$symbolMap)) {
            return $this->numericToMoney(
                mb_substr($monetary, 1),
                self::$symbolMap[$symbol]
            );
        }

        throw new InvalidArgumentException(
            "Failed to parse monetary string `{$monetary}`."
        );
    }

    /**
     * @param string $numeric
     * @param string $currency
     *
     * @return Money
     * @throws \InvalidArgumentException
     * @throws \Money\Exception\UnknownCurrencyException
     */
    private function numericToMoney(
        string $numeric,
        string $currency = 'GBP'
    ): Money
    {
        return Money::fromAmount(
            $this->numericToAmount($numeric, $currency),
            $currency
        );
    }

    /**
     * @param string $numeric
     * @param string $currency
     *
     * @return int
     * @throws \InvalidArgumentException
     * @throws \Money\Exception\UnknownCurrencyException
     */
    private function numericToAmount(string $numeric, string $currency): int
    {
        $invalid = new InvalidArgumentException(
            "`{$numeric}` is not a valid numerical amount."
        );

        if (!is_numeric($numeric)) {
            throw $invalid;
        }

        return (int) ($numeric * pow(
                10,
                $this->currencies->subunitFor(
                    new Currency($currency)
                )
            )
        );
    }
}
