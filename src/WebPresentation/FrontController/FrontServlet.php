<?php

declare(strict_types = 1);

namespace WebPresentation\FrontController;

use Throwable;
use ReflectionClass;
use ReflectionException;
use Exception\ApplicationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

class FrontServlet
{
    private $response;

    public function doGet(ServerRequest $request, Response $response): void
    {
        $command = $this->getCommand($request);
        $command->init($request, $response);
        $command->process();

        $this->response = $command->getResponse();
    }

    private function getCommand(ServerRequest $request): FrontCommand
    {
        try {
            return $this->getCommandClass($request)->newInstance();
        } catch (Throwable $e) {
            throw new ApplicationException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    private function getCommandClass(ServerRequest $request): ReflectionClass
    {
        $query = $request->getQueryParams();
        $className = sprintf("%s\\%sCommand", __NAMESPACE__, $query['command'] ?? null);

        try {
            $result = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            $result = new ReflectionClass(UnknownCommand::class);
        }

        return $result;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
