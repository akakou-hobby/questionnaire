<?php

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';


$json = file_get_contents("php://input");
$contents = json_decode($json, true);

$encoded_answers = base64_encode($contents["answers"]);

$cmd = __DIR__ . '/../../../sign verify';

$descriptorspec = array(
    0 => array("pipe", "r"), 
    1 => array("pipe", "w"), 
    2 => array("pipe", "w") 
);

$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    fwrite($pipes[0], $encoded_answers  . "\n");
    fwrite($pipes[0], $contents["signature"]);

    fclose($pipes[0]);

    $result = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    echo stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    proc_close($process);
}

if ($result != "verified") return;

$answer = ORM::for_table('answers')->create();

$answer->data = $contents["answers"];
$answer->signature = $contents["signature"];
$answer->form_id = $contents["form"];

$answer->save();

echo "done";
