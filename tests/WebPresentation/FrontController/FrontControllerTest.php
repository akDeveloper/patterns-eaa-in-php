<?php

declare(strict_types = 1);

namespace WebPresentation\FrontController;

use Laminas\Diactoros\Response;
use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

class FrontControllerTest extends TestCase
{
    private const SUCCESS_RESPONSE_BODY = "<h1>barelyWorks</h1>\n";

    public function testShouldHandleRequest(): void
    {
        $request = $this->getRequest();
        $f = new FrontServlet();
        $f->doGet($request, new Response());
        $response = $f->getResponse();

        $responseBody = $response->getBody()->__toString();

        $this->assertEquals(self::SUCCESS_RESPONSE_BODY, $responseBody);
    }

    private function getRequest(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals([], ['name' => 'barelyWorks', 'command' => 'Artist']);
    }
}
