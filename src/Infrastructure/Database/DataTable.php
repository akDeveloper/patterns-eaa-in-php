<?php

declare(strict_types = 1);

namespace Infrastructure\Database;

interface DataTable
{
    public function select(string $filter): array;
}
