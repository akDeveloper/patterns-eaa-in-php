<?php

declare(strict_types = 1);

namespace DataSource;

use ArrayIterator;
use Infrastructure\Database\Connection;
use Infrastructure\Database\PreparedStatement;

trait ConnectionHelper
{
    private $connection;

    private $statement;

    abstract protected function getData(): array;

    protected function getMockConnection(ArrayIterator $expectedResults): Connection
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->setMethods(['prepare'])
            ->getMock();

        $this->statement = $this->getMockPreparedStatement($expectedResults);
        $this->connection
            ->method('prepare')
            ->willReturn($this->statement);

        return $this->connection;
    }

    protected function getMockPreparedStatement(ArrayIterator $expectedResults): PreparedStatement
    {
        $preparedStatement = $this->getMockBuilder(PreparedStatement::class)
            ->setMethods(['bindValue', 'executeQuery', 'execute'])
            ->getMock();

        $preparedStatement->method('executeQuery')
            ->willReturn($expectedResults);

        return $preparedStatement;
    }

    protected function getLoadAllRecordSet(array $match = null): ArrayIterator
    {
        if ($match == null) {
            return new ArrayIterator($this->getData());
        }

        $data = array_filter($this->getData(), function ($item) use ($match) {
            return isset($item[$match[0]]) && $item[$match[0]] == $match[1];
        });

        return new ArrayIterator($data);
    }

    protected function getOneRecord(): ArrayIterator
    {
        $data = $this->getData();

        return new ArrayIterator([reset($data)]);
    }
}
