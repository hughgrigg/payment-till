<?php

namespace Till\Io\In;

use InvalidArgumentException;
use stdClass;

class JsonLineReader extends FormatLineReader
{
    /**
     * @return stdClass
     * @throws InvalidArgumentException
     */
    protected function parse(): stdClass
    {
        $decoded = json_decode($this->input);

        if (!$decoded || !is_object($decoded)) {
            throw new InvalidArgumentException(
                "Failed to decode JSON input `{$this->input}`."
            );
        }

        return $decoded;
    }
}
