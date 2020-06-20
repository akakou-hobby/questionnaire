<?php

require  __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Firebase\Auth\Token\Exception\InvalidToken;


Flight::set('flight.views.path', __DIR__ . '/../templates/');

Flight::map('auth', function(){
    $factory = (new Factory)->withServiceAccount(__DIR__ . '/../credentials.json');
    $auth = $factory->createAuth();

    $authorization = getallheaders()['Authorization'];
    $token = explode(" ", $authorization)[1];

    try {        
        $verifiedIdToken = $auth->verifyIdToken($token, true);
        $uid = $verifiedIdToken->getClaim('sub');
        Flight::json(array('id' => $uid));

    } catch (Exception $e){
        Flight::halt(401, 'HTTP 401 Unauthorized');
    }
});


Flight::route('/', function(){
    Flight::render('index');
});

Flight::route('/verify', function() {
    Flight::auth();

    echo "hello";
});

Flight::start();

