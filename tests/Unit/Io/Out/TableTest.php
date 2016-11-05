<?php

namespace Test\Unit\Io\Out;

use PHPUnit\Framework\TestCase;
use Till\Io\Out\Table;

class TableTest extends TestCase
{
    /**
     * Should get a tabular format.
     */
    public function testFormat()
    {
        $table = new Table();
        $table->addRow('foo', 'bar');
        $table->addRow('bar', 'foo');
        $this->assertEquals(
            <<<TABLE
-------------
| foo | bar |
-------------
| bar | foo |
-------------

TABLE
            ,
            (string) $table
        );
    }

    /**
     * Columns should be padded to the maximum length.
     */
    public function testColumnPadding()
    {
        $table = new Table();
        $table->addRow('foo', 'bar');
        $table->addRow('foobar', 'foo');
        $this->assertEquals(
            <<<TABLE
----------------
| foo    | bar |
----------------
| foobar | foo |
----------------

TABLE
            ,
            (string) $table
        );
    }

    /**
     * Rows with missing columns should have empty columns added.
     */
    public function testEmptyColumn()
    {
        $table = new Table();
        $table->addRow('foo', 'bar');
        $table->addRow('foobar');
        $this->assertEquals(
            <<<TABLE
----------------
| foo    | bar |
----------------
| foobar |     |
----------------

TABLE
            ,
            (string) $table
        );
    }
}
