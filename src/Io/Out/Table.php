<?php

namespace Till\Io\Out;

use Illuminate\Support\Collection;

class Table
{
    /** @var Collection|Collection[] */
    private $rows;

    /**
     * @param string[] $columns
     */
    public function addRow(string ...$columns)
    {
        $this->rows()->put($this->rows()->count(), new Collection($columns));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s%s%s%s%s',
            $this->divider(),
            PHP_EOL,
            $this->body(),
            $this->divider(),
            PHP_EOL
        );
    }

    /**
     * @return string
     */
    private function body(): string
    {
        return (new Collection())
            ->merge(
                $this->rows()
                    ->map(
                        function (Collection $row) {
                            return $this->printRow($row);
                        }
                    )
            )
            ->implode($this->divider().PHP_EOL);
    }

    /**
     * @return Collection|array[]
     */
    private function rows(): Collection
    {
        if ($this->rows === null) {
            $this->rows = new Collection();
        }

        return $this->rows;
    }

    /**
     * @return int
     */
    private function columnCount(): int
    {
        return (int) $this->rows()->max(
            function (Collection $columns) {
                return $columns->count();
            }
        );
    }

    /**
     * @param Collection $columns
     *
     * @return string
     */
    private function printRow(Collection $columns): string
    {
        while ($columns->count() < $this->columnCount()) {
            $columns->push('');
        }

        return sprintf(
            '| %s |%s',
            $columns->map(
                function (string $column, int $index) {
                    return $this->pad(
                        $column,
                        $this->columnWidth($index)
                    );
                }
            )->implode(' | '),
            PHP_EOL
        );
    }

    /**
     * @return int
     */
    private function width(): int
    {
        $width = 2; // left divider and margin
        for ($i = 0, $count = $this->columnCount(); $i < $count; $i++) {
            $width += $this->columnWidth($i) + 2; // right margin and divider
        }

        return $width;
    }

    /**
     * @param int $columnIndex
     *
     * @return int
     */
    private function columnWidth(int $columnIndex): int
    {
        return (int) $this->rows()->pluck($columnIndex)->max(
            function ($column) {
                return mb_strlen($column);
            }
        );
    }

    /**
     * @param string $text
     * @param int    $toLength
     *
     * @return string
     */
    private function pad(string $text, int $toLength): string
    {
        return $text.str_repeat(' ', $toLength - mb_strlen($text));
    }

    /**
     * @return string
     */
    private function divider(): string
    {
        return str_repeat('-', $this->width() + 1);
    }
}
