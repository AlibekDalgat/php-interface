<?php

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents('index.html'));
    return $response;
});


$app->post('/create-task', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $input_data = isset($data['input_data']) ? $data['input_data'] : '';

    $client = new \GuzzleHttp\Client();
    try {
        $res = $client->request('POST', 'http://localhost:8000/tasks', [
            'json' => ['input_data' => $input_data]
        ]);
        $response_data = json_decode($res->getBody(), true);
        $task_id = $response_data['task_id'];
        $response->getBody()->write(json_encode(['task_id' => $task_id]));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }
});

$app->get('/task-status/{task_id}', function (Request $request, Response $response, array $args) {
    $task_id = $args['task_id'];

    $client = new \GuzzleHttp\Client();
    try {
        $res = $client->request('GET', "http://localhost:8000/tasks/{$task_id}");
        $response_data = json_decode($res->getBody(), true);
        $response->getBody()->write(json_encode($response_data));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }
});

$app->run();

