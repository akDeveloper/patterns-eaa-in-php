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
        $dataMap->addColumn("lastname", "varchar", "lastName");
        $dataMap->addColumn("firstname", "varchar", "firstName");
        $dataMap->addColumn("number_of_dependents", "int", "numberOfDependents");

        var_dump($dataMap->columnList());
    }
}
