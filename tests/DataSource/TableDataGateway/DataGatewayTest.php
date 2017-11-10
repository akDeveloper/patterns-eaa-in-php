<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use BasePatterns\RecordSet\DataRow;
use BasePatterns\RecordSet\RecordSet;
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

        $this->assertInstanceOf(DataRow::class, $dataRow);
        $this->assertEquals('Jane', $dataRow->firstName);
    }

    public function testShouldLoadWithWhereClause()
    {
        $expectedRecordSet = $this->getLoadWhereRecordSet();
        $personGateway = new PersonGateway($this->getMockConnection($expectedRecordSet));
        $personGateway->loadWhere("`lastName` = ?", ['Doe']);

        $data = $personGateway->getData();

        $this->assertEquals(2, count($data));

        $first = reset($data);
        $this->assertInstanceOf(DataRow::class, $first);
    }

    public function testShouldUpdateRecord()
    {
        $recordSet = $this->getUpdateRecord();
        $personGateway = new PersonGateway($this->getMockConnection($recordSet));
        $personGateway->loadWhere('`id` = ?', [1]);

        $personGateway[1]->numberOfDependents = 10;
        $personGateway[1]->lastName = 'Bob';

        $personGateway->update();
    }

    private function getMockConnection(RecordSet $expectedResults): Connection
    {
        $connection = $this->getMockBuilder(Connection::class)
            ->setMethods(['prepare'])
            ->getMock();

        $connection->method('prepare')
            ->willReturn($this->getMockPreparedStatement($expectedResults));

        return $connection;
    }

    private function getMockPreparedStatement(RecordSet $expectedResults): PreparedStatement
    {
        $preparedStatement = $this->getMockBuilder(PreparedStatement::class)
            ->setMethods(['bindValue', 'executeQuery'])
            ->getMock();

        $preparedStatement->method('executeQuery')
            ->willReturn($expectedResults);

        return $preparedStatement;
    }

    private function getLoadAllRecordSet(): RecordSet
    {
        return new RecordSet(new ArrayIterator($this->getData()));
    }

    private function getLoadWhereRecordSet(): RecordSet
    {
        $data = array_filter($this->getData(), function ($row) {
            return $row['lastName'] === 'Doe';
        });

        return new RecordSet(new ArrayIterator($data));
    }

    private function getUpdateRecord(): RecordSet
    {
        $data = $this->getData();
        $row = reset($data);

        return new RecordSet(new ArrayIterator([$row]));
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
