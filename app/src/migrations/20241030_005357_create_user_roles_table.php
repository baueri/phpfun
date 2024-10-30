    <?php

        return [
            'up' => function() {
                // Write your migration code here
                db('CREATE TABLE user_roles (user_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(user_id, role_id))');
                db('ALTER TABLE `user_roles` ADD FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;');
                db('ALTER TABLE `user_roles` ADD FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;');
            },
            'down' => function() {
                // Write your migration code here
                db('DROP TABLE user_roles');
            }
        ];