<?php

namespace Test\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Till\Command\CsvReceiptCommand;
use Till\Command\JsonReceiptCommand;
use Till\Command\ReceiptCommand;
use Till\Command\XmlReceiptCommand;

class TabularReceiptTest extends TestCase
{
    const EXPECTED = <<<TABLE
------------------------------
| Item              | Price  |
------------------------------
| Baked Beans       | £0.50  |
------------------------------
| Washing Up Liquid | £0.72  |
------------------------------
| Rubber Gloves     | £1.50  |
------------------------------
| Bread             | £0.72  |
------------------------------
| Butter            | £0.83  |
------------------------------
|                   |        |
------------------------------
| Sub total         | £4.27  |
------------------------------
| Discounts         | -£0.50 |
------------------------------
|                   |        |
------------------------------
| Grand total       | £3.77  |
------------------------------

TABLE;

    /**
     * @param string $format
     * @param string $commandClass
     *
     * @dataProvider inputFormatProvider
     */
    public function testInputFormats(string $format, string $commandClass)
    {
        $receiptCommand = $this->makeCommand($commandClass);
        $receiptCommand->setInput(__DIR__."/../input/purchases.{$format}");

        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $input->method('getOption')
            ->with('output')
            ->willReturn(ReceiptCommand::OUTPUT_TABLE);
        $output->expects($this->once())
            ->method('write')
            ->with(self::EXPECTED);

        $receiptCommand->run($input, $output);
    }

    /**
     * @return array[]
     */
    public function inputFormatProvider()
    {
        return [
            ['csv', CsvReceiptCommand::class],
            ['json', JsonReceiptCommand::class],
            ['xml', XmlReceiptCommand::class],
        ];
    }

    /**
     * Get type-hinted receipt command for testing.
     *
     * @param string $commandClass
     *
     * @return ReceiptCommand
     */
    private function makeCommand(string $commandClass): ReceiptCommand
    {
        return new $commandClass();
    }
}
