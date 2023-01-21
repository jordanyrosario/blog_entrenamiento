<?php

require_once '../vendor/autoload.php';

$app = require '../Bootstrap.php';
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

$emitter = new SapiEmitter();
$response = $app->run();
if (403 === $response->getStatusCode()) {
    $data = processTemplate([], '403');
    $response->getBody()->write($data);
}if (404 === $response->getStatusCode()) {
    $s = $response->getBody();
    $size = $s->getSize();
    $data = processTemplate([], '404');
    $size ?: $response->getBody()->write($data);
}if (405 === $response->getStatusCode()) {
    $s = $response->getBody();
    $size = $s->getSize();
    $data = processTemplate([], '405');
    $size ?: $response->getBody()->write($data);
}
if (401 === $response->getStatusCode()) {
    $data = processTemplate([], '401');
    $response->getBody()->write($data);
}
$emitter->emit($response);
