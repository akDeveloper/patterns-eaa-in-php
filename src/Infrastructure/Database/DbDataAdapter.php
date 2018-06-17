<?php

declare(strict_types = 1);

namespace Infrastructure\Database;

use DataSource\Connection;
use BasePatterns\RecordSet\Row;
use BasePatterns\RecordSet\RecordSet;
use DataSource\TableDataGateway\DataAdapter;

class DbDataAdapter implements DataAdapter
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
            $this->updateRow($tableName, $row);
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

    private function updateRow(string $tableName, Row $row): void
    {
        if (!$row->hasChanges()) {
            return;
        }

        list($sqlCommand, $binds) = $this->createSqlForUpdate($tableName, $row->getChanges());

        $this->connection
            ->prepare($sqlCommand)
            ->execute($binds);
    }

    private function createSqlForUpdate(string $tableName, array $changes)
    {
        $sqlArray = [];
        $binds = [];
        foreach ($changes as $field => $value) {
            $sqlArray[] = sprintf("`%s`.%s = ?", $tableName, $field);
            $binds[] = $value;
        }

        return [
            sprintf('UPDATE `%s` SET %s', $tableName, implode(', ', $sqlArray)),
            $binds
        ];
    }
}
