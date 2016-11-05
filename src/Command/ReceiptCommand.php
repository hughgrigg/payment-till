<?php

namespace Till\Command;

use Cilex\Provider\Console\Command;
use DomainException;
use Money\Currencies\ISOCurrencies;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Till\Domain\Receipt;
use Till\Io\In\LineReader;
use Till\Io\In\MoneyParser;
use Till\Io\Out\ReceiptOutput;
use Till\Io\Out\TabularReceiptOutput;

abstract class ReceiptCommand extends Command
{
    const OUTPUT_TABLE = 'table';

    /** @var string */
    protected $input = 'php://stdin';

    /**
     * @param string $input
     */
    public function setInput(string $input)
    {
        $this->input = $input;
    }

    /**
     * @return LineReader
     */
    abstract protected function reader(): LineReader;

    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('receipt')
            ->setDescription(
                'Print a receipt for a till transaction from std in.'
            );

        $this->addOption(
            'output',
            'o',
            InputOption::VALUE_OPTIONAL,
            'Output format',
            self::OUTPUT_TABLE
        );
    }

    /**
     * {@inheritDoc}
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $receipt = new Receipt();
        foreach ($this->reader()->readLines() as $purchase) {
            $purchase->addToReceipt($receipt);
        }

        $output->write(
            $this->out(
                $input->getOption('output'),
                $receipt
            )->print()
        );
    }

    /**
     * @return MoneyParser
     */
    protected function moneyParser(): MoneyParser
    {
        return new MoneyParser(new ISOCurrencies());
    }

    /**
     * @param string  $format
     * @param Receipt $receipt
     *
     * @return ReceiptOutput
     * @throws \DomainException
     */
    private function out(string $format, Receipt $receipt): ReceiptOutput
    {
        if ($format === self::OUTPUT_TABLE) {
            return new TabularReceiptOutput($receipt);
        }

        throw new DomainException(
            "Unknown output format `{$format}`."
        );
    }
}
