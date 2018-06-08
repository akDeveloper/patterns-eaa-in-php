<?php

declare(strict_types = 1);

namespace MetadataMapping\Query;

use PHPUnit\Framework\TestCase;
use DataSource\DataMapper\Person;
use MetadataMapping\Metadata\DataMap;

class CriteriaTest extends TestCase
{
    public function testShouldGenerateSql(): void
    {
        $dataMap = new DataMap(Person::class, "people");
        $dataMap->addColumn("lastname", "varchar", "lastName");
        $dataMap->addColumn("firstname", "varchar", "firstName");
        $dataMap->addColumn("number_of_dependents", "int", "numberOfDependents");

        $c = Criteria::equalsTo("lastName", "Doe");

        $sql = $c->generateSql($dataMap);

        $this->assertNotNull($sql);
        $this->assertEquals("Doe", $c->getValue());
    }
}
