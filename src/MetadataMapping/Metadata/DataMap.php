<?php

declare(strict_types = 1);

namespace MetadataMapping\Metadata;

use ReflectionClass;
use Exception\ApplicationException;

class DataMap
{
    private $domainClass;

    private $tableName;

    private $columnMaps = [];

    public function __construct(string $domainClass, string $tableName)
    {
        $this->domainClass = new ReflectionClass($domainClass);
        $this->tableName = $tableName;
    }

    public function getDomainClass(): ReflectionClass
    {
        return $this->domainClass;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getColumnMaps(): array
    {
        return $this->columnMaps;
    }

    public function addColumn(
        string $columnName,
        string $type,
        string $fieldName
    ): void {
        $this->columnMaps[] = new ColumnMap(
            $columnName,
            $fieldName,
            $type,
            $this
        );
    }

    public function columnList(): string
    {
        $columnNames = array_map(function (ColumnMap $columnMap) {
            return $columnMap->getColumnName();
        }, $this->columnMaps);

        return " id," . implode(",", $columnNames);
    }

    public function getColumnForField(string $fieldName): string
    {
        foreach ($this->getColumnMaps() as $columnMap) {
            if ($columnMap->getFieldName() == $fieldName) {
                return $columnMap->getColumnName();
            }
        }

        throw new ApplicationException(
            sprintf("Unable to find column for `%s`", $fieldName)
        );
    }
}
