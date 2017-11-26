<?php

declare(strict_types = 1);

namespace BasePatterns\RecordSet;

use Countable;
use Traversable;

class RecordSet implements Countable
{
    private $tables = [];

    private $count = 0;

    public function getTable(string $tableName): Table
    {
        return $this->tables[$tableName];
    }

    public function load(Traversable $traversable, string $tableName)
    {
        $this->tables[$tableName] = new Table($tableName);
        $rows = [];
        foreach ($traversable as $row) {
            $rows[] = new Row($row);
        }
        $this->tables[$tableName]->load($rows);
        $this->count += count($rows);
    }

    public function count(): int
    {
        return $this->count;
    }
}
