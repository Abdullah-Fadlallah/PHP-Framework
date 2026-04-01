<?php

use GuzzleHttp\Psr7\ServerRequest; 
use HttpSoft\Emitter\SapiEmitter; 
use League\Route\Router; 

//---Controllers---
use app\Controllers\HomeController;
use app\Controllers\ProductController;


ini_set("display_errors", 1); 

require dirname(__DIR__) . "/vendor/autoload.php"; 

$request = ServerRequest::fromGlobals(); 

//---outer---
$router = new Router;

// get(path, handler/action)
$router->get('/', [HomeController::class, 'index']);

$router->get('/product/', [ProductController::class, 'index']);

$router->get('/product/{id:number}', [ProductController::class, 'show']);
//---endRouter---


$response = $router->dispatch($request);
$emitter = new SapiEmitter();
$emitter->emit($response); 



