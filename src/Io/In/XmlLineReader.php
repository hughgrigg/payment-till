<?php

namespace Till\Io\In;

use SimpleXMLElement;
use stdClass;

class XmlLineReader extends FormatLineReader
{
    /**
     * @return stdClass
     */
    protected function parse(): stdClass
    {
        $xml = new SimpleXMLElement($this->input);

        $items = [];
        foreach ($xml->item as $item) {
            $items[] = (object) [
                'description' => (string) $item->description,
                'price'       => (string) $item->price,
            ];
        }

        return (object) [
            'items' => $items,
        ];
    }
}
