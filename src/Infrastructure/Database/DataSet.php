<?php

declare(strict_types = 1);

namespace Infrastructure\Database;

interface DataSet
{
    public function getTable(string $tableName): DataTable;
}
