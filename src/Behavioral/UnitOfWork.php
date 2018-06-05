<?php

declare(strict_types = 1);

namespace Behavioral;

use SplObjectStorage;
use BasePatterns\LayerSupertype\DomainObject;

class UnitOfWork
{
    private $newObjects = [];

    private $dirtyObjects = [];

    private $removedObjects = [];

    public function __construct()
    {
        $this->newObjects = new SplObjectStorage();
        $this->dirtyObjects = new SplObjectStorage();
        $this->removedObjects = new SplObjectStorage();
    }

    public function registerNew(DomainObject $object): void
    {
        $this->newObjects->attach($object);
    }

    public function registerDirty(DomainObject $object): void
    {
        if (!$this->removedObjects->contains($object)
            && !$this->dirtyObjects->contains($object)
            && !$this->newObjects->contains($objects)
        ) {
            $this->dirtyObjects->attach($object);
        }
    }

    public function registerRemoved(DomainObject $object): void
    {
        if ($this->newObjects->contains($object)) {
            $this->newObjects->detach($object);

            return;
        }

        $this->dirtyObjects->detach($object);
        if (!$this->removedObjects->contains($object)) {
            $this->removedObjects->attach($object);
        }
    }
}
