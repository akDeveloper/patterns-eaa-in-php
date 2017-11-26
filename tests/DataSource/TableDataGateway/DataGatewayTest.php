<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use Traversable;
use ArrayIterator;
use PHPUnit\Framework\TestCase;
use BasePatterns\RecordSet\Row;
use Infrastructure\Database\Connection;
use Infrastructure\Database\PreparedStatement;

class DataGatewayTest extends TestCase
{
    public function testShouldLoadAll()
    {
        $expectedRecordSet = $this->getLoadAllRecordSet();
        $personGateway = new PersonGateway($this->getMockConnection($expectedRecordSet));
        $personGateway->loadAll();

        $dataRow = $personGateway[2];

        $this->assertInstanceOf(Row::class, $dataRow);
        $this->assertEquals('Jane', $dataRow->firstName);
    }

    public function testShouldLoadWithWhereClause()
    {
        $expectedRecordSet = $this->getLoadWhereRecordSet();
        $personGateway = new PersonGateway($this->getMockConnection($expectedRecordSet));
        $personGateway->loadWhere("`lastName` = ?", ['Doe']);

        $data = $personGateway->getData();

        $this->assertEquals(2, $data->count());

        $first = $personGateway[1];
        $this->assertInstanceOf(Row::class, $first);
    }

    private function getMockConnection(Traversable $expectedResults): Connection
    {
        $connection = $this->getMockBuilder(Connection::class)
            ->setMethods(['prepare'])
            ->getMock();

        $connection->method('prepare')
            ->willReturn($this->getMockPreparedStatement($expectedResults));

        return $connection;
    }

    private function getMockPreparedStatement(Traversable $expectedResults): PreparedStatement
    {
        $preparedStatement = $this->getMockBuilder(PreparedStatement::class)
            ->setMethods(['bindValue', 'executeQuery'])
            ->getMock();

        $preparedStatement->method('executeQuery')
            ->willReturn($expectedResults);

        return $preparedStatement;
    }

    private function getLoadAllRecordSet(): Traversable
    {
        return new ArrayIterator($this->getData());
    }

    private function getLoadWhereRecordSet(): Traversable
    {
        $data = array_filter($this->getData(), function ($row) {
            return $row['lastName'] === 'Doe';
        });

        return new ArrayIterator($data);
    }

    private function getUpdateRecord(): Traversable
    {
        $data = $this->getData();
        $row = reset($data);

        return new ArrayIterator([$row]);
    }

    private function getData(): array
    {
        return [
            ['id' => 1, 'firstName' => 'John', 'lastName' => 'Doe', 'numberOfDependents' => 2],
            ['id' => 2, 'firstName' => 'Jane', 'lastName' => 'Doe', 'numberOfDependents' => 1],
            ['id' => 3, 'firstName' => 'John', 'lastName' => 'Smith', 'numberOfDependents' => 3],
        ];
    }
}
