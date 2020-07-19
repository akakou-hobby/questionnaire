<?php

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';


$results = [
    "user_token" => "",
    "answers" => []  
];

$admin_token = $_GET['admin_token'];

$form = ORM::for_table('forms')
        ->where("admin_token", $admin_token)
        ->find_one();

$results["user_token"] = $form->user_token;


$answers = ORM::for_table('answers')
        ->where("form_id", $form->id)
        ->find_array();


foreach ($answers as $answer) {
    $data = json_decode($answer["data"]);
    
    foreach ($data as $value) {
        // todo: refactor
        $result = [
            'question' => $value->question,
            'answer' => $value->answer
        ];

        array_push($results["answers"], $result);
    }
}

echo json_encode($results);
