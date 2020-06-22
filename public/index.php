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

Flight::route('/sign', function() {
    // Flight::auth();

    $descriptorspec = array(
        0 => array("pipe", "r"), 
        1 => array("pipe", "w"), 
        2 => array("pipe", "w") 
    );

    $cmd = __DIR__ . '/../sign sign';

    $process = proc_open($cmd, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        $blind_digest = Flight::request()->data->$blind_digest;

        $blind_digest = $_POST['blind_digest'];

        fwrite($pipes[0], $blind_digest);
        fclose($pipes[0]);

        echo stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // echo stream_get_contents($pipes[2]);
        // fclose($pipes[2]);

        proc_close($process);
    }
});

Flight::start();

