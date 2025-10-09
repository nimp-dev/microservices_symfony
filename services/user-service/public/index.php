<?php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$routes = new RouteCollection();
$routes->add('users_list', new Route('/users', ['_controller' => function() {
    $data = [
        ['id' => 1, 'name' => 'John'],
        ['id' => 2, 'name' => 'Alice']
    ];
    return new Response(json_encode($data), 200, ['Content-Type' => 'application/json']);
}]));
$routes->add('user_ping', new Route('/users/ping', ['_controller' => function() {
    return new Response(json_encode(['service' => 'user-service', 'status' => 'ok']), 200, ['Content-Type' => 'application/json']);
}]));


$context = new RequestContext();
$context->fromRequest(Request::createFromGlobals());
$matcher = new UrlMatcher($routes, $context);
$request = Request::createFromGlobals();

try {
    $params = $matcher->match($request->getPathInfo());
    $response = call_user_func($params['_controller'], $request);
} catch (Exception $e) {
    $response = new Response(json_encode(['error' => 'Not found']), 404, ['Content-Type' => 'application/json']);
}
$response->send();
