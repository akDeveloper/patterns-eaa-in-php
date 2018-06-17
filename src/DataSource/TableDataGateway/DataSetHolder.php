<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use DataSource\Connection;
use BasePatterns\RecordSet\RecordSet;
use Infrastructure\Database\DbDataAdapter;

class DataSetHolder
{
    /**
     * @var \BasePatterns\RecordSet\RecordSet
     */
    public $data;

    private $dataAdapters = [];

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->data = new RecordSet();
    }

    public function fillData(
        string $query,
        string $tableName,
        array $params = []
    ): void {
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
