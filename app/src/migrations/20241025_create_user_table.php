<?php

return [
    'up' => <<<SQL
        CREATE TABLE user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )
    SQL,
    'down' => 'DROP TABLE user'
];

