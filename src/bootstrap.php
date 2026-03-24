<?php

use GuzzleHttp\Psr7\ServerRequest; 
use GuzzleHttp\Psr7\Response; 
use GuzzleHttp\Psr7\Utils; 
use HttpSoft\Emitter\SapiEmitter; 



ini_set("display_errors", 1); 




require dirname(__DIR__) . "/vendor/autoload.php"; 




$request = ServerRequest::fromGlobals(); 




$path = $request->getUri()->getPath() ; 


$page = match ($path) {
    "/"     => "home",
    "/home" => "home"
};







ob_start();




require dirname(__DIR__)  . "/public/{$page}.php";





$content = ob_get_clean();














$stream = Utils::streamFor($content);





$response = new Response();






$response = $response->withStatus(418) 
                    ->withHeader("X-Powered-By","PHP")                                                                            
                    ->withBody($stream);







$emitter = new SapiEmitter();
$emitter->emit($response); 




