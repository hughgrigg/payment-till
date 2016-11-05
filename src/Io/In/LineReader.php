<?php

namespace Till\Io\In;

use Generator;
use Till\Domain\Purchase;

interface LineReader
{
    /**
     * Iterate on input purchases.
     *
     * @return Generator|Purchase[]
     */
    public function readLines(): Generator;
}
