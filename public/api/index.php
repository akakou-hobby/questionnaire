<?php

require  __DIR__ . '/../../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Firebase\Auth\Token\Exception\InvalidToken;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;

function auth() {
    $factory = (new Factory)->withServiceAccount(__DIR__ . '/../../credentials.json');
    $auth = $factory->createAuth();

    $authorization = getallheaders()['Authorization'];
    $token = explode(" ", $authorization)[1];

    try {        
        $verifiedIdToken = $auth->verifyIdToken($token, true);
        return true;
    } catch (Exception $e){
        return false;
    }
}

$app->post('/sign', function (Request $request, Response $response, array $args) {
    if (!auth()) {
        return "error";
    }

    $descriptorspec = array(
        0 => array("pipe", "r"), 
        1 => array("pipe", "w"), 
        2 => array("pipe", "w") 
    );

    $cmd = __DIR__ . '/../../sign sign';

    $process = proc_open($cmd, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        $blind_digest = $_POST['blind_digest'];

        fwrite($pipes[0], $blind_digest);
        fclose($pipes[0]);

        echo stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        echo stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        proc_close($process);
    }
});


$app->run();
