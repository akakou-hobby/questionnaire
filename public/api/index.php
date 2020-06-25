<?php

require  __DIR__ . '/../../vendor/autoload.php';

use \Firebase\JWT\JWT;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

define('PRODUCT_NAME', 'questionnaire-e0c0b');

$dbh = new PDO('sqlite:' . __DIR__ . '/../db.sqlite3');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$app = new \Slim\App;

function auth() {
    $authorization = getallheaders()['Authorization'];

    if (preg_match('#\ABearer\s+(.+)\z#', $authorization, $m)) {
        $jwt = $m[1];

        $pkeys_raw = file_get_contents("https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com");
        $pkeys = json_decode($pkeys_raw, true);
        
        try {
            $decoded = JWT::decode($jwt, $pkeys, array('RS256'));
        } catch (Exception $e){
            return null;
        }

        if ($decoded->aud == PRODUCT_NAME) {
            return $decoded;
        }
        else {
            return null;
        }
    }
}

$app->post('/sign', function (Request $request, Response $response, array $args) {
    if (!auth()) {
        return "authentication failed";
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
