<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();
    $container['db'] = function ($c) {
        $db = $c->get('settings')['db'];
        $pdo = new PDO($db);

        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    };
};
