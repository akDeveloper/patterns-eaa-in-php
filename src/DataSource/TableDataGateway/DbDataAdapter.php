<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use Infrastructure\DataBase\DataSet;
use Infrastructure\DataBase\Connection;

class DbDataAdapter
{
    private $query;

    private $connection;

    public function __construct(string $query, Connection $connection)
    {
        $this->query = $query;
        $this->connection = $connection;
    }

    public function update(DataSet $data, string $table): int
    {
    }

    public function fill(DataSet $data, string $table): int
    {
    }
}
