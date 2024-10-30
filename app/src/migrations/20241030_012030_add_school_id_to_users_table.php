    <?php

        return [
            'up' => function() {
                // Write your migration code here
                db('ALTER TABLE user ADD COLUMN school_id INT AFTER email; ALTER TABLE user ADD FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE ON UPDATE CASCADE;');
            },
            'down' => function() {
                // Write your migration code here
                db('ALTER TABLE user DROP COLUMN school_id');
            }
        ];