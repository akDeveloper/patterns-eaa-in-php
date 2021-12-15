<?php

declare(strict_types = 1);

namespace BasePatterns\RecordSet;

class Table
{
    private string $tableName;

    private array $rows = [];

    private array $columns = [];

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function select(string $filter): array
    {
        return [];
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function load(array $rows)
    {
        $this->rows = $rows;
    }
}
