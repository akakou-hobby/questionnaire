<?php
require  __DIR__ . '/../../../vendor/autoload.php';

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';


$user_token = $_GET['user_token'];
$questions = ORM::for_table('forms')
        ->where("user_token", $user_token)
        ->find_one();

$results = [
    "questions" => json_decode($questions->questions),
    "pubkey" => $questions->pubkey,
];


echo json_encode($results);