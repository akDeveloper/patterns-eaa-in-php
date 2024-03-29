<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use ArrayAccess;
use DataSource\Connection;
use BasePatterns\RecordSet\Row;
use BasePatterns\RecordSet\Table;
use BasePatterns\RecordSet\RecordSet;

abstract class DataGateway implements ArrayAccess
{
    /**
     * @var DataSetHolder
     */
    public $holder;

    abstract public function getTableName(): string;

    public function __construct(Connection $connection)
    {
        $this->holder = new DataSetHolder($connection);
    }

    public function getData(): RecordSet
    {
        return $this->holder->data;
    }

    public function getTable(): Table
    {
        return $this->getData()->getTable($this->getTableName());
    }

    public function loadAll(): void
    {
        $commandString = sprintf("SELECT * FROM `%s`", $this->getTableName());
        $this->holder->fillData($commandString, $this->getTableName());
    }

    public function loadWhere(string $whereClause, array $params): void
    {
        $commandString = sprintf("SELECT * FROM `%s` WHERE %s", $this->getTableName(), $whereClause);
        $this->holder->fillData($commandString, $this->getTableName(), $params);
    }

    public function update(): void
    {
        $this->holder->update();
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->holder->data[$this->getTableName()]);
    }

    public function offsetGet($offset)
    {
        $rows = $this->getTable()->getRows();
        $hit = array_filter($rows, function (Row $row) use ($offset) {
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
