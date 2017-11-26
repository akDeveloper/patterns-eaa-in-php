<?php

declare(strict_types = 1);

namespace Infrastructure\Database;

use Traversable;

interface PreparedStatement
{
    public function bindValue($parameter, $value, int $dataType): void;

    public function executeQuery(array $params = []): Traversable;
}
