<?php

declare(strict_types = 1);

namespace DataSource;

use Iterator;

interface PreparedStatement
{
    public function bindValue($parameter, $value): void;

    public function execute(array $params = []): void;

    public function executeQuery(array $params = []): Iterator;
}
