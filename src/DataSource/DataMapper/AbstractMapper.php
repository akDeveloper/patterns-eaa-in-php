<?php

declare(strict_types = 1);

namespace DataSource\DataMapper;

use ArrayIterator;
use DataSource\Connection;
use Behavioral\IdentityMap;
use BasePatterns\RecordSet\Row;
use Exception\ApplicationException;
use BasePatterns\RecordSet\RecordSet;
use MetadataMapping\Metadata\DataMap;
use DataSource\Exception\SQLException;
use BasePatterns\LayerSupertype\DomainObject;

abstract class AbstractMapper
{
    private Connection $db;

    private ?DataMap $dataMap = null;

    abstract protected function findStatement(): string;

    abstract protected function loadDataMap(): DataMap;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    public function getDataMap()
    {
        return $this->dataMap = $this->dataMap ?: $this->loadDataMap();
    }

    public function findObjectsWhere(string $whereClause, array $bindValues): ArrayIterator
    {
        $sql = sprintf(
            "SELECT %s FROM %s WHERE %s",
            $this->getDataMap()->columnList(),
            $this->getDataMap()->getTableName(),
            $whereClause
        );

        try {
            $stmt = $this->db->prepare($sql);
            $rs = $stmt->executeQuery($bindValues);
            return $this->loadAll($rs);
        } catch (SQLException $e) {
            throw new ApplicationException(
                $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }

    protected function abstractFind(int $id): DomainObject
    {
        if ($result = IdentityMap::get($id, $this->getDataMap()->getDomainClass()->getName()) ?? null) {
            return $result;
        }

        try {
            $findStatement = $this->db->prepare($this->findStatement());
            $findStatement->bindValue(1, $id);
            $rs = $findStatement->executeQuery();

            return $this->load($rs->current());
        } catch (SQLException $e) {
            throw new ApplicationException(
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
        } catch (SQLException $e) {
            throw new ApplicationException(
                $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }

    protected function load(array $row): DomainObject
    {
        $id = $row['id'];
        if ($result = IdentityMap::get($id, $this->getDataMap()->getDomainClass()->getName()) ?? null) {
            return $result;
        }

        $result = $this->getDataMap()->getDomainClass()->newInstanceWithoutConstructor();
        $result->setId($id);
        $this->loadFields($row, $result);
        IdentityMap::set($result);

        return $result;
    }

    protected function loadAll(ArrayIterator $rs): ArrayIterator
    {
        $result = \array_map([$this, 'load'], $rs->getArrayCopy());

        return new ArrayIterator($result);
    }

    private function loadFields(array $row, DomainObject $result): void
    {
        foreach ($this->dataMap->getColumnMaps() as $columnMap) {
            $columnMap->setField($result, $row[$columnMap->getColumnName()]);
        }
    }
}
