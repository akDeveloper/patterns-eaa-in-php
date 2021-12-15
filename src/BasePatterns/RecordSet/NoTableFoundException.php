<?php

declare(strict_types = 1);

namespace BasePatterns\RecordSet;

use RuntimeException;

class NoTableFoundException extends RuntimeException
{
    public function __construct(string $tableName)
    {
        parent::__construct(
            \sprintf('Table `%s` not found', $tableName)
        );
    }
}
