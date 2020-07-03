<?php
require  __DIR__ . '/../../vendor/autoload.php';

require __DIR__ . '/../../src/auth.php';
require __DIR__ . '/../../src/db.php';

$user = auth();
if (!$user) {
    echo "authentication failed";
    exit;
}

$questionnaire = ORM::for_table('questionnaires')->create();

$json = file_get_contents("php://input");
$contents = json_decode($json, true);

$questionnaire->questions = $contents["questions"];
$questionnaire->user_id = $user->user_id;

$questionnaire->save(); 

echo $questionnaire->id;
?>