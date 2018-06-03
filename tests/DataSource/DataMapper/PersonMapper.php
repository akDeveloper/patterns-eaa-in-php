<?php

declare(strict_types = 1);

namespace DataSource\DataMapper;

use ArrayIterator;

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
