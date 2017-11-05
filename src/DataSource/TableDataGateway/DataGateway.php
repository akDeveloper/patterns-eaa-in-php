<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use Infrastructure\DataBase\Connection;

abstract class DataGateway
{
    public $holder;

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->holder = new DataSetHolder();
    }
}
