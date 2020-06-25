<?php

require __DIR__ . '/../../src/auth.php';
require __DIR__ . '/../../src/db.php';


if (!auth()) {
    echo "authentication failed";
    exit;
}

$json = file_get_contents("php://input");
$contents = json_decode($json, true);

$answers = $contents["answers"];
$signature = $contents["signature"];

$stmt = $db->prepare("
    INSERT INTO answers(questionnaire_id, data, signature)
    VALUES (?, ?, ?);
    ");

$stmt->execute([1, $answers, $signature]);

echo "done";
