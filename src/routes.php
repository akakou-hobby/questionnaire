<?php
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {    
	$container->get('logger')->info("Slim-Skeleton '/' route");
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
};
