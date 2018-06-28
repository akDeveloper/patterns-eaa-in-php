<?php

declare(strict_types = 1);

namespace WebPresentation\ApplicationController\DomainCommand;

use WebPresentation\ApplicationController\DomainCommand;

class NullAssetCommand implements DomainCommand
{
    public function run(array $params): void
    {
    }
}