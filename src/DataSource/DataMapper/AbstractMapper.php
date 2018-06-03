<?php

declare(strict_types = 1);

namespace DataSource\DataMapper;

use PDOException;
use ArrayIterator;
use BasePatterns\RecordSet\Row;
use BasePatterns\RecordSet\RecordSet;
use Infrastructure\Database\Connection;
use BasePatterns\LayerSupertype\DomainObject;

abstract class AbstractMapper
{
    private $db;

    protected $loadedMap = [];

    abstract protected function findStatement(): string;

    /**
     * @throws SQLException
     */
    abstract protected function doLoad(int $id, array $row);

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
            $findStatement->bindValue(1, $id);
            $rs = $findStatement->executeQuery();

            return $this->load($rs->current());
        } catch (PDOException $e) {
            throw new SQLException(
                $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }

    protected function findMany(StatementSource $source): ArrayIterator
    {
        try {
            $stmt = $this->db->prepare($source->getSql());
            foreach ($source->getParameters() as $i => $value) {
                $stmt->bindValue($i, $value);
            }
            $rs = $stmt->executeQuery();

            return $this->loadAll($rs);
        } catch (PDOException $e) {
            throw new SQLException(
                $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }

    protected function load(array $row): DomainObject
    {
        $id = $row['id'];
        if ($result = $this->loadedMap[$id] ?? null) {
            return $result;
        }

        $result = $this->doLoad($id, $row);
        $this->loadedMap[$id] = $result;

        return $result;
    }

    protected function loadAll(ArrayIterator $rs): ArrayIterator
    {
        $result = array_map(function (array $row) {
            return $this->load($row);
        }, $rs->getArrayCopy());

        return new ArrayIterator($result);
    }
}
