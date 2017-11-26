<?php

declare(strict_types = 1);

namespace BasePatterns\RecordSet;

use Infrastructure\Database\PreparedStatement;

class Table
{
    private $tableName;

    private $rows = [];

    private $columns = [];

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function select(string $filter): array
    {
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
