CREATE TABLE IF NOT EXISTS forms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id VARCHAR(100),
    questions VARCHAR(2000),
    admin_token INTEGER,
    user_token INTEGER,
    prikey VARCHAR(4000),
    pubkey VARCHAR(4000)
);

CREATE TABLE IF NOT EXISTS answers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    form_id INTEGER,
    data VARCHAR(1000),
    signature VARCHAR(1000)
);

CREATE TABLE IF NOT EXISTS signlogs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    form_id INTEGER,
    user_id VARCHAR(100)
);
