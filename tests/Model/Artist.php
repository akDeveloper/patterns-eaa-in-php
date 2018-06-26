<?php

declare(strict_types = 1);

namespace Model;

use BasePatterns\LayerSupertype\DomainObject;

class Artist extends DomainObject
{
    private $name;

    public static function findNamed(string $name): self
    {
        $artist = new self(1);
        $artist->name = $name;

        return $artist;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
