<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use ArrayAccess;
use BasePatterns\RecordSet\DataRow;
use Infrastructure\DataBase\Connection;

abstract class DataGateway implements ArrayAccess
{
    public $holder;

    private $connection;

    abstract public function getTableName(): string;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->holder = new DataSetHolder($connection);
    }

    public function getData(): array
    {
        return $this->holder->data[$this->getTableName()];
    }

    public function loadAll(): void
    {
        $commandString = sprintf("SELECT * FROM %s", $this->getTableName());
        $this->holder->fillData($commandString, $this->getTableName());
    }

    public function loadWhere(string $whereClause, array $params): void
    {
        $commandString = sprintf("SELECT * FROM %s WHERE %s", $this->getTableName(), $whereClause);
        $this->holder->fillData($commandString, $this->getTableName(), $params);
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data[$this->getTableName()]);
    }

    public function offsetGet($offset)
    {
        $rows = $this->holder->data[$this->getTableName()];
        $hit = array_filter($rows, function (DataRow $row) use ($offset) {
            return $row->id == $offset;
        });

        return reset($hit) ?: null;
    }

    public function offsetSet($offset, $value): void
    {
        false;
    }

    public function offsetUnset($offset): void
    {
        false;
    }
}
