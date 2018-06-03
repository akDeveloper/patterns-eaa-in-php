<?php

declare(strict_types = 1);

namespace MetadataMapping\Query;

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

    private function __construct(
        string $sqlOperator,
        string $field,
        string $value
    ) {
        $this->sqlOperator = $sqlOperator;
        $this->field = $field;
        $this->value = $value;
    }
}
