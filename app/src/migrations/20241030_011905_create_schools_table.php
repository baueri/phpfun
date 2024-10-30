    <?php

        return [
            'up' => function() {
                // Write your migration code here
                db('CREATE TABLE schools (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL, 
                    city VARCHAR(255) NOT NULL, 
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)');
            },
            'down' => function() {
                // Write your migration code here
                db('DROP TABLE schools');
            }
        ];