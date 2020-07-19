<?php
require  __DIR__ . '/../../../vendor/autoload.php';

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';


$form = ORM::for_table('forms')->create();

$json = file_get_contents("php://input");
$contents = json_decode($json, true);

$form->questions = json_encode($contents["data"]);
$form->user_id = $user->user_id;

$bytes = openssl_random_pseudo_bytes(16);
$form->user_token = bin2hex($bytes);

$bytes = openssl_random_pseudo_bytes(16);
$form->admin_token = bin2hex($bytes);

$form->save(); 

echo $form->admin_token;
?>