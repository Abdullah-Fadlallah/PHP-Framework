<?php

use GuzzleHttp\Psr7\ServerRequest; 
use GuzzleHttp\Psr7\Response; 
use GuzzleHttp\Psr7\Utils; 
use HttpSoft\Emitter\SapiEmitter; 
use League\Route\Router; 



ini_set("display_errors", 1); 




require dirname(__DIR__) . "/vendor/autoload.php"; 




$request = ServerRequest::fromGlobals(); 





$router = new Router;

$router->map('GET', '/', function () {

$stream = Utils::streamFor("home page");

$response = new Response();

$response = $response->withBody($stream);

return $response;
});


$router->get('/product/{id:number}', function ($request, $args) {

$id = $args['id'];
$stream = Utils::streamFor("product id: $id");

$response = new Response();

$response = $response->withBody($stream);

return $response;
});








$emitter = new SapiEmitter();
$emitter->emit($response); 



