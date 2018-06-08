<?php

declare(strict_types = 1);

namespace MetadataMapping\Query;

use MetadataMapping\Metadata\DataMap;

class Criteria
{
    private $sqlOperator;

    private $field;

    private $value;

    public static function greaterThan(string $field, string $value): self
    {
        return new self(" > ", $field, $value);
    }

    public static function lessThan(string $field, string $value): self
    {
        return new self(" < ", $field, $value);
    }

    public static function equalsTo(string $field, string $value): self
    {
        return new self(" = ", $field, $value);
    }

    private function __construct(
        string $sqlOperator,
        string $field,
        string $value
    ) {
        $this->sqlOperator = $sqlOperator;
        $this->field = $field;
        $this->value = $value;
    }

    public function generateSql(DataMap $dataMap)
    {
        return sprintf(
            "%s%s?",
            $dataMap->getColumnForField($this->field),
            $this->sqlOperator
        );
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
