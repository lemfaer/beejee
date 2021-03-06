CREATE TABLE users (
    id       INTEGER      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    email    VARCHAR(255) NOT NULL UNIQUE,
    login    VARCHAR(255) NULL UNIQUE DEFAULT NULL,
    password VARCHAR(255) NULL DEFAULT NULL,
    created  TIMESTAMP    NOT NULL DEFAULT now(),
    updated  TIMESTAMP    NULL DEFAULT NULL ON UPDATE now()
)

ENGINE = InnoDB;
