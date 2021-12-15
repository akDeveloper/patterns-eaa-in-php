<?php

declare(strict_types = 1);

namespace BasePatterns\RecordSet;

use Countable;
use Traversable;

class RecordSet implements Countable
{
    private array $tables = [];

    private int $count = 0;

    public function getTable(string $tableName): Table
    {
        if (\array_key_exists($tableName, $this->tables) === false) {
            throw new NoTableFoundException($tableName);
        }

        return $this->tables[$tableName];
    }

    public function load(Traversable $traversable, string $tableName): void
    {
        $this->tables[$tableName] = new Table($tableName);
        $rows = [];
        foreach ($traversable as $row) {
            $rows[] = new Row($row);
        }
        $this->tables[$tableName]->load($rows);
        $this->count += \count($rows);
    }

    public function count(): int
    {
        return $this->count;
    }
}
