<?php

declare(strict_types = 1);

namespace MetadataMapping\Metadata;

use PHPUnit\Framework\TestCase;
use DataSource\DataMapper\Person;

class DataMapTest extends TestCase
{
    public function testShouldCreateDataMap()
    {
        $dataMap = new DataMap(Person::class, "people");
        $dataMap->addColumn("id", "int", "id");
        $dataMap->addColumn("lastname", "varchar", "lastName");
        $dataMap->addColumn("firstname", "varchar", "firstName");
        $dataMap->addColumn("number_of_dependents", "int", "numberOfDependents");

        $person = $dataMap->getDomainClass()->newInstanceWithoutConstructor();

        $row = [
            'id' => 1,
            'lastname' => 'Doe',
            'firstname' => 'John',
            'number_of_dependents' => 3,
        ];
        foreach ($dataMap->getColumnMaps() as $columnMap) {
            $columnMap->setField($person, $row[$columnMap->getColumnName()]);
        }

        $this->assertEquals(1, $person->getId());
        $this->assertEquals('John', $person->getFirstName());
        $this->assertEquals('Doe', $person->getLastName());
        $this->assertEquals(3, $person->getNumberOfDependents());
    }
}
