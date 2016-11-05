<?php

namespace Till\Io\In;

use Generator;
use InvalidArgumentException;
use stdClass;
use Till\Domain\Line;
use Till\Domain\Purchase;

abstract class FormatLineReader implements LineReader
{
    /** @var string */
    protected $input;

    /** @var MoneyParser */
    private $moneyParser;

    /**
     * @param string      $input
     * @param MoneyParser $moneyParser
     */
    public function __construct($input, MoneyParser $moneyParser)
    {
        $this->input = $input;
        $this->moneyParser = $moneyParser;
    }

    /**
     * Iterate on input purchases.
     *
     * @return Generator|Purchase[]
     * @throws \Money\Exception\UnknownCurrencyException
     * @throws InvalidArgumentException
     */
    public function readLines(): Generator
    {
        $input = $this->parse();
        if (empty($input->items) || !is_array($input->items)) {
            throw new InvalidArgumentException(
                'Input must contain items.'
            );
        }

        foreach ($input->items as $item) {
            yield $this->itemToLine($item);
        }
    }

    /**
     * @return stdClass
     */
    abstract protected function parse(): stdClass;

    /**
     * @param stdClass $item
     *
     * @return Line
     * @throws \Money\Exception\UnknownCurrencyException
     * @throws \InvalidArgumentException
     */
    private function itemToLine(stdClass $item): Line
    {
        if (empty($item->description)) {
            throw new InvalidArgumentException(
                'Item must have a description.'
            );
        }

        if (empty($item->price)) {
            throw new InvalidArgumentException(
                'Item must have a price.'
            );
        }

        return Line::make(
            $item->description,
            $this->moneyParser->parse($item->price)
        );
    }
}
