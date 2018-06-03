<?php

declare(strict_types = 1);

namespace DataSource\DataMapper;

interface StatementSource
{
    public function getSql(): string;

    public function getParameters(): array;
}
