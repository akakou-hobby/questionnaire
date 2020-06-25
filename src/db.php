<?php

$db = new PDO('sqlite:' . __DIR__ . '/../db.sqlite3');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$db->exec("CREATE TABLE IF NOT EXISTS answers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        questionnaire_id INTEGER,
        data VARCHAR(1000),
        signature VARCHAR(1000)
    )");
