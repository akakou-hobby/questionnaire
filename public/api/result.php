<?php

require __DIR__ . '/../../src/auth.php';
require __DIR__ . '/../../src/db.php';


$results = [];

$questionnaire_id = 1;
$answers = ORM::for_table('answers')
        ->where("questionnaire_id", $questionnaire_id)
        ->find_array();

foreach ($answers as $answer) {
    $data = json_decode($answer["data"]);
    
    foreach ($data as $key => $value) {
        // todo: refactor
        $result = [
            'question' => $key,
            'answer' => $value
        ];
    }

    array_push($results, $result);
}

echo json_encode($results);
