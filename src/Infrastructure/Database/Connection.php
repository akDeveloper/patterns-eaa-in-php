<?php

declare(strict_types = 1);

namespace Infrastructure\Database;

interface Connection
{
    public function prepare(string $query): PreparedStatement;
}
