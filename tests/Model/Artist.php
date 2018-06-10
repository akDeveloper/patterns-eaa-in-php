<?php

declare(strict_types = 1);

namespace Model;

class Artist
{
    private $name;

    public static function findNamed(string $name): self
    {
        return new self($name);
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
