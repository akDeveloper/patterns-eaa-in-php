<?php

declare(strict_types = 1);

namespace WebPresentation\ApplicationController;

use WebPresentation\Forward;
use Psr\Http\Message\ResponseInterface as ServerResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

class FrontServlet
{
    public function service(ServerRequest $request, ServerResponse $response): ServerResponse
    {
        $params = $this->getParameterMap($request);
        $appController = $this->getApplicationController($request);
        $commandString = $params["command"] ?? null;
        $domainCommand = $appController->getDomainCommand($commandString, $params);
        $domainCommand->run($params);

        $viewPage = sprintf(
            "/%s.php",
            $appController->getView($commandString, $params)
        );

        return $this->forward($viewPage, $request, $response);
    }

    protected function getParameterMap(ServerRequest $request): array
    {
        return array_merge(
            $request->getQueryParams() ?? [],
            $request->getParsedBody() ?? []
        );
    }

    protected function forward(
        string $viewPage,
        ServerRequest $request,
        ServerResponse $response
    ): ServerResponse {
        $f = new Forward($request, $response);

        return $f->sendResponse($viewPage);
    }

    private function getApplicationController(ServerRequest $request): ApplicationController
    {
        $target = trim($request->getUri()->getPath(), "/");

        $class = sprintf("%s\%sApplicationController", __NAMESPACE__, $target);

        return new $class();
    }
}
