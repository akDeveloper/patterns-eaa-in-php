<?php

declare(strict_types = 1);

namespace DataSource;

interface Connection
{
    public function prepare(string $query): PreparedStatement;
}
