<?php

require  __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;

define('PRODUCT_NAME', 'questionnaire-e0c0b');

function auth() {
    $jwt = $_POST['token'];

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
