CREATE TABLE tasks (
    id          INTEGER      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id     INTEGER      NOT NULL,
    description TEXT         NULL,
    status      ENUM('new', 'done') NOT NULL DEFAULT 'new',
    created     TIMESTAMP    NOT NULL DEFAULT now(),
    updated     TIMESTAMP    NULL DEFAULT NULL ON UPDATE now(),
    FOREIGN KEY (user_id) REFERENCES users(id)
)

ENGINE = InnoDB;
