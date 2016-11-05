<?php

namespace Till\Command;

use Till\Io\In\CsvLineReader;
use Till\Io\In\LineReader;

class CsvReceiptCommand extends ReceiptCommand
{
    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('csv');
    }

    /**
     * @return LineReader
     */
    protected function reader(): LineReader
    {
        return new CsvLineReader(
            fopen($this->input, 'rb'),
            $this->moneyParser()
        );
    }
}
