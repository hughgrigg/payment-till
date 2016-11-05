<?php

namespace Till\Command;

use Till\Io\In\LineReader;
use Till\Io\In\XmlLineReader;

class XmlReceiptCommand extends ReceiptCommand
{
    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('xml');
    }

    /**
     * @return LineReader
     */
    protected function reader(): LineReader
    {
        return new XmlLineReader(
            file_get_contents($this->input),
            $this->moneyParser()
        );
    }
}
