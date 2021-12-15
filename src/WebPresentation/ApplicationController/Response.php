<?php

declare(strict_types = 1);

namespace WebPresentation\ApplicationController;

use ReflectionClass;
use ReflectionException;
use Exception\ApplicationException;

class Response
{
    private ReflectionClass $domainCommand;

    private string $viewUrl;

    public function __construct(string $domainCommand, string $viewUrl)
    {
        $this->domainCommand = new ReflectionClass($domainCommand);
        $this->viewUrl = $viewUrl;
    }

    public function getDomainCommand(): DomainCommand
    {
        try {
            return $this->domainCommand->newInstance();
        } catch (ReflectionException $e) {
            throw new ApplicationException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    public function getViewUrl(): string
    {
        return $this->viewUrl;
    }
}
