<?php

declare(strict_types = 1);

namespace WebPresentation\ApplicationController;

use Zend\Diactoros\Response;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

class ApplicationControllerTest extends TestCase
{
    public function testShouldHandleReturnRequest(): void
    {
        $request = $this->getReturnRequest();
        $f = new FrontServlet();
        $f->service($request, new Response());
        $response = $f->getResponse();

        $this->assertEquals("<h1>Return</h1>\n", $response->getBody()->__toString());
    }

    public function testShouldHandleDamageRequest(): void
    {
        $request = $this->getDamageRequest();
        $f = new FrontServlet();
        $f->service($request, new Response());
        $response = $f->getResponse();

        $this->assertEquals("<h1>Lease Damage</h1>\n", $response->getBody()->__toString());
    }

    private function getReturnRequest(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals(["REQUEST_URI" => "/Asset?assetID=1001&command=return"], ['assetID' => '1001', 'command' => 'return']);
    }

    private function getDamageRequest(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals(["REQUEST_URI" => "/Asset?assetID=1001&command=damage"], ['assetID' => '1001', 'command' => 'damage']);
    }
}
