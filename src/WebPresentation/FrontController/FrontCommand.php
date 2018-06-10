<?php

declare(strict_types = 1);

namespace WebPresentation\FrontController;

use Throwable;
use Zend\Diactoros\Stream;
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
        $level = ob_get_level();
        ob_start();
        ob_implicit_flush(0);
        try {
            include $target;
        } catch (Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }

        $content = ob_get_clean();

        $stream = $this->response->getBody();
        $stream->rewind();
        $stream->write($content);
        $this->response = $this->response->withBody($stream);
    }
}
