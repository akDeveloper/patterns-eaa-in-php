<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use Traversable;
use ArrayIterator;
use DataSource\Connection;
use PHPUnit\Framework\TestCase;
use BasePatterns\RecordSet\Row;
use DataSource\ConnectionHelper;
use DataSource\PreparedStatement;

class DataGatewayTest extends TestCase
{
    use ConnectionHelper;

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

    public function testShouldUpdateData()
    {
        $expectedRecordSet = $this->getUpdateRecord();
        $personGateway = new PersonGateway($this->getMockConnection($expectedRecordSet));

        $this->statement
            ->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([10]));

        $personGateway->loadWhere("`id` = ?", [1]);
        $person = $personGateway[1];
        $person->numberOfDependents = 10;

        $personGateway->update();
    }

    private function getLoadWhereRecordSet(): ArrayIterator
    {
        $data = array_filter($this->getData(), function ($row) {
            return $row['lastName'] === 'Doe';
        });

        return new ArrayIterator($data);
    }

    private function getUpdateRecord(): ArrayIterator
    {
        $data = $this->getData();
        $row = reset($data);

        return new ArrayIterator([$row]);
    }

    protected function getData(): array
    {
        return [
            ['id' => 1, 'firstName' => 'John', 'lastName' => 'Doe', 'numberOfDependents' => 2],
            ['id' => 2, 'firstName' => 'Jane', 'lastName' => 'Doe', 'numberOfDependents' => 1],
            ['id' => 3, 'firstName' => 'John', 'lastName' => 'Smith', 'numberOfDependents' => 3],
        ];
    }
}
