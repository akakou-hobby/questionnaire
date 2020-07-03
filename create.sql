CREATE TABLE IF NOT EXISTS questionnaires (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id VARCHAR(100),
    questions VARCHAR(2000)
);

CREATE TABLE IF NOT EXISTS answers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    questionnaire_id INTEGER,
    data VARCHAR(1000),
    signature VARCHAR(1000)
);

CREATE TABLE IF NOT EXISTS signlogs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    questionnaire_id INTEGER,
    user_id VARCHAR(100)
);
