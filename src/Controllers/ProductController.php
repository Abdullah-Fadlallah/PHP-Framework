<?php

declare(strict_types=1);

namespace app\Controllers;

use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest; 


class ProductController
{
    public function index(): Response
    {
        $stream = Utils::streamFor("product page");
        $response = new Response;
        $response = $response->withBody($stream);
        return $response;
    }

    public function show(ServerRequest $request, array $args): Response
    {
        $id = $args['id'];
        $stream = Utils::streamFor("product id: $id");
        $response = new Response();
        $response = $response->withBody($stream);
        return $response;
    }
}