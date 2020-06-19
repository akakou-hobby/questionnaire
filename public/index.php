<?php 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//composerで追加したパッケージを一括でインポートしてくれる
require __DIR__. "/../vendor/autoload.php";

//インスタンス作成
$app = new \Slim\App;

//http://localhost/{適当な文字列が$argsのnameというkeyにvalueが格納される}
$app->get('/[{name}]', function ($request, $response, $args) {
    $name = isset($args['name']) ? $args['name'] : "World" ;
    $response->getBody()->write("Hello{$name}");

    return $response;
});

$app->run();