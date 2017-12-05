<?php

declare(strict_types = 1);

namespace Infrastructure\DataBase;

use BasePatterns\RecordSet\Row;
use BasePatterns\RecordSet\RecordSet;

class DbDataAdapter
{
    private $query;

    private $connection;

    public function __construct(string $query, Connection $connection)
    {
        $this->query = $query;
        $this->connection = $connection;
    }

    public function update(RecordSet $data, string $tableName): int
    {
        foreach ($data->getTable($tableName)->getRows() as $row) {
            $this->updateRow($row);
        }

        return 0;
    }

    public function fill(RecordSet $data, string $tableName): int
    {
        $traversable = $this->connection
            ->prepare($this->query)
            ->executeQuery();

        $data->load($traversable, $tableName);

        return $data->count();
    }

    private function updateRow(Row $row): void
    {
        if (!$row->hasChanges()) {
            return;
        }

        $changes = $row->getChanges();
    }
}