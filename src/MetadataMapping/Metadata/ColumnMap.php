<?php

declare(strict_types = 1);

namespace MetadataMapping\Metadata;

use ReflectionProperty;
use ReflectionException;
use Exception\ApplicationException;
use BasePatterns\LayerSupertype\DomainObject;

class ColumnMap
{
    private string $columnName;

    private string $fieldName;

    private string $type;

    private DataMap $dataMap;

    private ReflectionProperty $field;

    public function __construct(
        string $columnName,
        string $fieldName,
        string $type,
        DataMap $dataMap
    ) {
        $this->columnName = $columnName;
        $this->fieldName = $fieldName;
        $this->type = $type;
        $this->dataMap = $dataMap;
        $this->initField();
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setField(DomainObject $object, $value): void
    {
        try {
            $this->field->setValue($object, $value);
        } catch (ReflectionException $e) {
            throw new ApplicationException(
                sprintf("Error in setting %s", $this->fieldName),
                -1,
                $e
            );
        }
    }

    private function initField(): void
    {
        try {
            $this->field = new ReflectionProperty(
                $this->dataMap->getDomainClass()->getName(),
                $this->fieldName
            );
            $this->field->setAccessible(true);
        } catch (ReflectionException $e) {
            throw new ApplicationException(
                sprintf("Unable to set up field: %s", $this->fieldName),
                -1,
                $e
            );
        }
    }
}
