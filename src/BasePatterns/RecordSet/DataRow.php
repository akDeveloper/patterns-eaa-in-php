<?php

declare(strict_types = 1);

namespace BasePatterns\RecordSet;

use InvalidArgumentException;

class DataRow
{
    private $row;

    public function __construct(array $row)
    {
        $this->row = $row;
    }

    public function isNull(string $column)
    {
        return $this->exists($column)
            && $this->row[$column] === null;
    }

    public function exists(string $column)
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
}
