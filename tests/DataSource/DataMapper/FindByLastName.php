<?php

declare(strict_types = 1);

namespace DataSource\DataMapper;

class FindByLastName implements StatementSource
{
    private $lastName;

    public function __construct(string $lastName)
    {
        $this->lastName = $lastName;
    }

    public function getSql(): string
    {
        return sprintf(
            "SELECT %s FROM people WHERE UPPER(lastname) LIKE UPPER(?) ORDER BY lastname",
            PersonMapper::COLUMNS
        );
    }

    public function getParameters(): array
    {
        return [$this->lastName];
    }
}
