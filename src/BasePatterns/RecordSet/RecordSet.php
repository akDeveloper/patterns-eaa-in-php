<?php

declare(strict_types = 1);

namespace BasePatterns\RecordSet;

use Traversable;

class RecordSet
{
    private $rows = [];

    private $traversable;

    public function __construct(Traversable $traversable)
    {
        $this->traversable = $traversable;
    }

    public function getRows(): array
    {
        if (empty($this->rows)) {
            foreach ($this->traversable as $row) {
                $this->rows[] = new DataRow($row);
            }
        }

        return $this->rows;
    }
}
