<?php

namespace Ozag\Skeleton\Application\Http\Controller;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TestController
{
    public function test(Request $request, Response $response)
    {
        $response->getBody()->write(json_encode([
            'test' => true
        ]));

        $response = $response->withHeader('Content-type', 'application/json');

        return $response;
    }
}
