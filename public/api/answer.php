<?php

require __DIR__ . '/../../src/auth.php';
require __DIR__ . '/../../src/db.php';


if (!auth()) {
    echo "authentication failed";
    exit;
}

$json = file_get_contents("php://input");
$contents = json_decode($json, true);

$answer = ORM::for_table('answers')->create();

$answer->data = $contents["answers"];
$answer->signature = $contents["signature"];
$answer->questionnaire_id = 1;

$answer->save();

echo "done";
