<?php

declare(strict_types = 1);

namespace Behavioral;

use SplObjectStorage;
use DataSource\Connection;
use DataSource\DataMapper\AbstractMapper;
use BasePatterns\LayerSupertype\DomainObject;

class UnitOfWork
{
    private $newObjects = [];

    private $dirtyObjects = [];

    private $removedObjects = [];

    private $mappers = [];

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->newObjects = new SplObjectStorage();
        $this->dirtyObjects = new SplObjectStorage();
        $this->removedObjects = new SplObjectStorage();
        $this->connection = $connection;
    }

    public function registerNew(DomainObject $object): void
    {
        $this->newObjects->attach($object);
    }

    public function registerDirty(DomainObject $object): void
    {
        if (!$this->removedObjects->contains($object)
            && !$this->dirtyObjects->contains($object)
            && !$this->newObjects->contains($object)
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

    public function registerDataMapper(string $className, string $mapperClassName): void
    {
        $this->mappers[$className] = new $mapperClassName($this->connection);
    }

    public function getDataMapper(string $className): AbstractMapper
    {
        if (array_key_exists($className, $this->mappers)) {
            return $this->mappers[$className];
        }

        throw new \DomainException(
            sprintf("Unable to find mapper for class `%s`", $className)
        );
    }
}
