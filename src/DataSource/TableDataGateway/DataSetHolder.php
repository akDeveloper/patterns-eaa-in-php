<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use Infrastructure\DataBase\Connection;

class DataSetHolder
{
    public $data = [];

    private $dataAdapters = [];

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fillData(string $query, string $tableName)
    {
    }
}
