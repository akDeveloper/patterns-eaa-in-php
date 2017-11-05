<?php

declare(strict_types = 1);

namespace DataSource\DataMapper;

use PDO;
use PDOException;
use RuntimeException;
use BasePatterns\RecordSet\RecordSet;
use Infrastructure\Database\Connection;
use BasePatterns\LayerSupertype\DomainObject;

abstract class AbstractMapper
{
    private $db;

    protected $loadedMap = [];

    abstract protected function findStatement(): string;

    abstract protected function doLoad(): DomainObject;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    protected function abstractFind(int $id): DomainObject
    {
        if ($result = $this->loadedMap[$id] ?? null) {
            return $result;
        }

        try {
            $findStatement = $this->db->prepare($this->findStatement());
            $findStatement->bindValue(1, $id, PDO::PARAM_INT);
            $rs = $findStatement->executeQuery();

            return $this->load($rs);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function load(RecordSet $rs): DomainObject
    {
    }
}
