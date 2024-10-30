    <?php

        return [
            'up' => function() {
                // Write your migration code here
                db('CREATE TABLE roles (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL)');
            },
            'down' => function() {
                // Write your migration code here
                db('DROP TABLE roles');
            }
        ];