<?php

require __DIR__ . '/../../src/auth.php';
require __DIR__ . '/../../src/db.php';


if (!auth()) {
    echo "authentication failed";
    exit;
}

$result = [];

$questionnaire_id = 1;
$answers = ORM::for_table('answers')
        ->where("questionnaire_id", $questionnaire_id)
        ->find_array();

foreach ($answers as $answer) {
    $data = json_decode($answer["data"]);
    array_push($result, $data);
}

echo json_encode($result);
