<?php
require  __DIR__ . '/../../../vendor/autoload.php';

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';


$results = [];

$form_id = $_GET['id'];
$questions = ORM::for_table('forms')
        ->where("id", $form_id)
        ->find_one();

echo $questions->questions;
