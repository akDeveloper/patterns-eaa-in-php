<?php

declare(strict_types = 1);

namespace Infrastructure\Database;

interface DataAdapter
{
    public function update(DataSet $data, string $table): int;

    public function fill(DataSet $data, string $table): int;
}
