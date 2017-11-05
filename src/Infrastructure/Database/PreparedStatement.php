<?php

declare(strict_types = 1);

namespace Infrastructure\Database;

interface PreparedStatement
{
    public function bindValue($parameter, $value, int $dataType): void;

    public function executeQuery(): RecordSet;
}
