<?php

require  __DIR__ . '/../../vendor/autoload.php';

require __DIR__ . '/../../src/auth.php';
require __DIR__ . '/../../src/db.php';

$questionnaire_id = $_POST['questionnaire'];

$user = auth();
if (!$user) {
    echo "authentication failed";
    exit;
}

$logs = ORM::for_table('signlogs')
    ->where('user_id', $user->user_id)
    ->where('questionnaire_id', $questionnaire_id)
    ->count();

if ($logs) {
    echo "[error] same user can't post mutltple.";
    exit;
}

$descriptorspec = array(
    0 => array("pipe", "r"), 
    1 => array("pipe", "w"), 
    2 => array("pipe", "w") 
);

$cmd = __DIR__ . '/../../sign sign';

$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    $blind_digest = $_POST['blinded_digest'];

    fwrite($pipes[0], $blind_digest);
    fclose($pipes[0]);

    echo stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    echo stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    proc_close($process);
}

$logs = ORM::for_table('signlogs')->create();

$logs->user_id = $user->user_id;
$logs->questionnaire_id = $questionnaire_id;

$logs->save();