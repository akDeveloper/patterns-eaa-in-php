<?php

declare(strict_types = 1);

namespace WebPresentation\ApplicationController;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

class FrontServlet
{
    public function service(ServerRequest $request, Response $response): void
    {
        $params = $this->getParameterMap($request);
        $appController = $this->getApplicationController($request);
        $command = $params["command"] ?? null;
        $domainCommand = $appController->getDomainCommand($commandString, $params);
        $domainCommand->run($params);

        $viewPage = sprintf(
            "/%s.php",
            $appController->getView($commandString, $params)
        );

        $this->forward($viewPage, $request, $response);
    }

    protected function getParameterMap(ServerRequest $request): array
    {
        return array_merge(
            $request->getQueryParams() ?? [],
            $request->getParsedBody() ?? []
        );
    }

    private function getApplicationController(ServerRequest $request): ApplicationController
    {
    }
}
