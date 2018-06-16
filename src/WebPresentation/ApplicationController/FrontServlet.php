<?php

declare(strict_types = 1);

namespace WebPresentation\ApplicationController;

use WebPresentation\FrontController\IOException;
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
        if (!file_exists($viewPage)) {
            throw new IOException(
                sprintf("File `%s` does not exist.", $viewPage)
            );
        }
        $level = ob_get_level();
        ob_start();
        ob_implicit_flush(0);
        try {
            include $viewPage;
        } catch (Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }

        $content = ob_get_clean();

        $stream = $response->getBody();
        $stream->rewind();
        $stream->write($content);
        $response = $response->withBody($stream);

        return $response;
    }

    private function getApplicationController(ServerRequest $request): ApplicationController
    {
        $target = trim($request->getUri()->getPath(), "/");

        $class = sprintf("%s\%sApplicationController", __NAMESPACE__, $target);

        return new $class();
    }
}
