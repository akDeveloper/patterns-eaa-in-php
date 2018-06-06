<?php

declare(strict_types = 1);

namespace DataSource\DataMapper;

use ArrayIterator;
use MetadataMapping\Metadata\DataMap;

class PersonMapper extends AbstractMapper
{
    const COLUMNS = " id, lastname, firstname, number_of_dependents ";

    public function find(int $id): Person
    {
        return $this->abstractFind($id);
    }

    public function findByLastName(string $lastName): ArrayIterator
    {
        return $this->findMany(new FindByLastName($lastName));
    }

    protected function loadDataMap(): DataMap
    {
        $dataMap = new DataMap(Person::class, "people");
        $dataMap->addColumn("id", "int", "id");
        $dataMap->addColumn("lastname", "varchar", "lastName");
        $dataMap->addColumn("firstname", "varchar", "firstName");
        $dataMap->addColumn("number_of_dependents", "int", "numberOfDependents");

        return $dataMap;
    }

    protected function doLoad(int $id, array $row)
    {
        return new Person(
            $id,
            $row['firstname'],
            $row['lastname'],
            $row['number_of_dependents']
        );
    }

    protected function findStatement(): string
    {
        return sprintf(
            "SELECT %s FROM people WHERE id = ?",
            self::COLUMNS
        );
    }
}
