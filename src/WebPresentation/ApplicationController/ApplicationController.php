<?php

declare(strict_types = 1);

namespace WebPresentation\ApplicationController;

interface ApplicationController
{
    public function getDomainCommand(string $command, array $params): DomainCommand;

    public function getView(string $command, array $params): string;
}
