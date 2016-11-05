<?php

namespace Till\Io\In;

use Generator;
use InvalidArgumentException;
use Till\Domain\Line;
use Till\Domain\Purchase;

/**
 * Take purchases from standard input as CSV.
 */
class CsvLineReader implements LineReader
{
    private $input;

    /** @var MoneyParser */
    private $moneyParser;

    /**
     * @param             $input
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
        while ($line = fgetcsv($this->input)) {
            yield $this->parseLine($line);
        }
    }

    /**
     * @param string[] $row
     *
     * @return Line
     * @throws \Money\Exception\UnknownCurrencyException
     * @throws InvalidArgumentException
     */
    private function parseLine(array $row): Line
    {
        if (count($row) < 2) {
            throw new InvalidArgumentException(
                'Price and description columns are required in CSV.'
            );
        }

        return Line::make($row[0], $this->moneyParser->parse($row[1]));
    }
}
