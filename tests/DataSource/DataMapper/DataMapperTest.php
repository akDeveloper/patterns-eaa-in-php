<?php

declare(strict_types = 1);

namespace DataSource\DataMapper;

use PHPUnit\Framework\TestCase;
use DataSource\ConnectionHelper;

class DataMapperTest extends TestCase
{
    use ConnectionHelper;

    public function testShouldFetchOnePerson(): void
    {
        $expectedResults = $this->getOneRecord();
        $mapper = new PersonMapper($this->getMockConnection($expectedResults));

        $person = $mapper->find(1);

        $this->assertInstanceOf(Person::class, $person);
    }

    public function testShouldFetchManyPersons(): void
    {
        $expectedResults = $this->getLoadAllRecordSet(['lastname', 'Doe']);
        $mapper = new PersonMapper($this->getMockConnection($expectedResults));

        $personCollection = $mapper->findByLastName("Doe");
        $this->assertEquals(2, $personCollection->count());
        $this->assertInstanceOf(Person::class, $personCollection->current());
    }

    protected function getData(): array
    {
        return [
            ['id' => 1, 'firstname' => 'John', 'lastname' => 'Doe', 'number_of_dependents' => 2],
            ['id' => 2, 'firstname' => 'Jane', 'lastname' => 'Doe', 'number_of_dependents' => 1],
            ['id' => 3, 'firstname' => 'John', 'lastname' => 'Smith', 'number_of_dependents' => 3],
        ];
    }
}
