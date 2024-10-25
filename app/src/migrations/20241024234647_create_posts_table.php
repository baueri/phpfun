    <?php
        return [
            'up' => function() {
                db('CREATE TABLE posts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    body TEXT NOT NULL,
                    author INT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
                    
                    ALTER TABLE posts ADD FOREIGN KEY (author) REFERENCES users(id);
                )');
            },
            'down' => function() {
                db('DROP TABLE posts');
            }
        ];