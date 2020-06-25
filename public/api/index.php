<?php

require  __DIR__ . '/../../vendor/autoload.php';

use \Firebase\JWT\JWT;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

define('PRODUCT_NAME', 'questionnaire-e0c0b');

$app = new \Slim\App;

$container = $app->getContainer();

$container['db'] = function ($c) {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../../db.sqlite3');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->exec("CREATE TABLE IF NOT EXISTS answers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            questionnaire_id INTEGER,
            data VARCHAR(1000),
            signature VARCHAR(1000)
        )");

    return $pdo;
};

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

$app->post('/answer', function (Request $request, Response $response, array $args) {
    if (!auth()) {
        return "authentication failed";
    }

    $json = file_get_contents("php://input");
    $contents = json_decode($json, true);

    $answers = $contents["answers"];
    $signature = $contents["signature"];

    $stmt = $this->db->prepare("
        INSERT INTO answers(questionnaire_id, data, signature)
        VALUES (?, ?, ?);
        ");

    $stmt->execute([1, $answers, $signature]);
});




$app->run();
