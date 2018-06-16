<?php

declare(strict_types = 1);

namespace WebPresentation\ApplicationController;

interface DomainCommand
{
    public function run(array $params): void;
}
