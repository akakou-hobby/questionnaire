<?php
require  __DIR__ . '/../../../vendor/autoload.php';

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';


$form = ORM::for_table('forms')->create();

$json = file_get_contents("php://input");
$contents = json_decode($json, true);

$form->questions = json_encode($contents["data"]);
$form->user_id = $user->user_id;

$form->save(); 

echo $form->id;
?>