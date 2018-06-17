<?php

declare(strict_types = 1);

namespace WebPresentation;

use Throwable;
use Zend\Diactoros\Stream;
use WebPresentation\Exception\IOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

class Forward
{
    private $request;

    private $response;

    public function __construct(ServerRequest $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function sendResponse(string $target): Response
    {
        if (!file_exists($target)) {
            throw new IOException(
                sprintf("File `%s` does not exist.", $viewPage)
            );
        }
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

        return $this->response;
    }
}
