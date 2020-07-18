<?php
require  __DIR__ . '/../../../vendor/autoload.php';

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';


$results = [];

$questionnaire_id = $_GET['id'];
$questions = ORM::for_table('questionnaires')
        ->where("id", $questionnaire_id)
        ->find_one();

echo $questions->questions;
