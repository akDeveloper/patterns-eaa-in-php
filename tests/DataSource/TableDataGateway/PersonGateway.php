<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

class PersonGateway extends DataGateway
{
    const TABLE_NAME = 'persons';

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }
}
