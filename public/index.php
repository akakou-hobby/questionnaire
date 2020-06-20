<?php

require  __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Firebase\Auth\Token\Exception\InvalidToken;


# flight config
Flight::set('flight.views.path', __DIR__ . '/../templates/');

Flight::route('/', function(){
    Flight::render('index');
});

Flight::route('/verify', function() {
    $factory = (new Factory)->withServiceAccount(__DIR__ . '/../credentials.json');
    $auth = $factory->createAuth();

    $token = Flight::request()->data->token;

    try {        
        $verifiedIdToken = $auth->verifyIdToken($token, true);
        $uid = $verifiedIdToken->getClaim('sub');
        Flight::json(array('id' => $uid));

    } catch (Exception $e){
        Flight::json(array('status' => 'failed'));
    } 
});

Flight::start();

