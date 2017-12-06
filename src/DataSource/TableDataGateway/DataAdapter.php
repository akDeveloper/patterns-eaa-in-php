<?php

declare(strict_types = 1);

namespace DataSource\TableDataGateway;

use BasePatterns\RecordSet\RecordSet;

interface DataAdapter
{
    public function update(RecordSet $data, string $table): int;

    public function fill(RecordSet $data, string $table): int;
}
