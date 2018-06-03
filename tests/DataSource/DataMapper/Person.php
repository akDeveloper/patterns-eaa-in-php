<?php

declare(strict_types = 1);

namespace DataSource\DataMapper;

use BasePatterns\LayerSupertype\DomainObject;

class Person extends DomainObject
{
    private $lastName;

    private $firstName;

    private $numberOfDependents;

    public function __construct(
        int $id,
        string $lastName,
        string $firstName,
        int $numberOfDependents
    ) {
        parent::__construct($id);
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->numberOfDependents = $numberOfDependents;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getNumberOfDependents(): int
    {
        return $this->numberOfDependents;
    }
}
