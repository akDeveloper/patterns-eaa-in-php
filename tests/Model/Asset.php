<?php

declare(strict_types = 1);

namespace Model;

class Asset
{
    private $id;

    private $status = AssetStatus::ON_LEASE;

    public function find(string $id): self
    {
        return new self($id);
    }

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getAssetStatus(): string
    {
        return $this->status;
    }
}
