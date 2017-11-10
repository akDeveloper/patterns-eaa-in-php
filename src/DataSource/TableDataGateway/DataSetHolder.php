<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use Infrastructure\Database\Connection;

class DataSetHolder
{
    /**
     * @var \Infrastructure\Database\DataSet
     */
    public $data;

    private $dataAdapters = [];

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fillData(string $query, string $tableName, array $params = [])
    {
        if (array_key_exists($tableName, $this->dataAdapters)) {
            throw new MultipleLoadException();
        }
        $da = new DbDataAdapter($query, $this->connection);
        $da->fill($this->data, $tableName);
        $this->dataAdapters[$tableName] = $da;
    }

    public function update(): void
    {
        foreach ($this->dataAdapters as $table => $da) {
            $da->update($this->data, $table);
        }
    }
}
