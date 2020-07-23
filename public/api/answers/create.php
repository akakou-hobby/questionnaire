<?php

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';


$json = file_get_contents("php://input");
$contents = json_decode($json, true);

$encoded_answers = base64_encode($contents["answers"]);

$form = ORM::for_table('forms')
    ->where('user_token', $contents["user_token"])
    ->find_one();

$cmd = __DIR__ . '/../../../sign verify';

$descriptorspec = array(
    0 => array("pipe", "r"), 
    1 => array("pipe", "w"), 
    2 => array("pipe", "w") 
);

$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    fwrite($pipes[0], $encoded_answers  . "\n");
    fwrite($pipes[0], $contents["signature"] . "\n");
    fwrite($pipes[0], $form->pubkey . "\n");

    fclose($pipes[0]);

    $result = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    echo stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    proc_close($process);
}

if ($result != "verifyed") {
    echo "can't verifyed";
    return;
};

$answer = ORM::for_table('answers')->create();

$answer->data = $contents["answers"];
$answer->signature = $contents["signature"];

$user_token = $contents["user_token"];
$answer->form_id = ORM::for_table('forms')
        ->where("user_token", $user_token)
        ->find_one()
        ->id;

$answer->save();

echo "done";
