<?php

declare(strict_types = 1);

namespace WebPresentation\FrontController;

use WebPresentation\Forward;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

abstract class FrontCommand
{
    protected $request;

    protected $response;

    abstract public function process(): void;

    public function init(ServerRequest $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    protected function forward(string $target): void
    {
        $f = new Forward($this->request, $this->response);

        $this->response = $f->sendResponse($target);
    }
}
