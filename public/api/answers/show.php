<?php

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';


$results = [];

$form_id = $_GET['id'];
$answers = ORM::for_table('answers')
        ->where("form_id", $form_id)
        ->find_array();


foreach ($answers as $answer) {
    $data = json_decode($answer["data"]);
    
    foreach ($data as $value) {
        // todo: refactor
        $result = [
            'question' => $value->question,
            'answer' => $value->answer
        ];

        array_push($results, $result);
    }
}

echo json_encode($results);
