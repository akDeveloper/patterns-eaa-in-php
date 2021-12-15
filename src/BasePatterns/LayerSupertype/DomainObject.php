<?php

declare(strict_types = 1);

namespace BasePatterns\LayerSupertype;

class DomainObject
{
    protected int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
