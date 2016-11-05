<?php

namespace Till\Command;

use Till\Io\In\JsonLineReader;
use Till\Io\In\LineReader;

class JsonReceiptCommand extends ReceiptCommand
{
    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('json');
    }

    /**
     * @return LineReader
     */
    protected function reader(): LineReader
    {
        return new JsonLineReader(
            file_get_contents($this->input),
            $this->moneyParser()
        );
    }
}
