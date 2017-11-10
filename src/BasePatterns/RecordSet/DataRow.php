<?php

declare(strict_types = 1);

namespace BasePatterns\RecordSet;

use InvalidArgumentException;

class DataRow
{
    private $row;

    private $changes = [];

    public function __construct(array $row)
    {
        $this->row = $row;
    }

    public function isNull(string $column): bool
    {
        return $this->exists($column)
            && $this->row[$column] === null;
    }

    public function exists(string $column): bool
    {
        return array_key_exists($column, $this->row);
    }

    public function __get(string $name)
    {
        if ($this->exists($name)) {
            return $this->row[$name];
        }

        throw new InvalidArgumentException(
            sprintf("Column with name `%s` does not exist.", $name)
        );
    }

    public function __set(string $name, $value): void
    {
        if ($this->exists($name)) {
            $this->row[$name] = $value;

            $this->changes[$name] = $value;

            return;
        }

        throw new InvalidArgumentException(
            sprintf("Column with name `%s` does not exist.", $name)
        );
    }

    public function hasChanges(): bool
    {
        return !empty($this->changes);
    }

    public function getChanges()
    {
        return $this->changes;
    }
}
