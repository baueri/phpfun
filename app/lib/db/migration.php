<?php

require_once LIB . 'db/db.php';

/**
 * @throws Exception
 */
function migrate(string $direction = 'up'): void
{
    if (!db_table_exists('migrations')) {
        db('CREATE TABLE migrations (migration VARCHAR(255) PRIMARY KEY, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)');
    }

    $migrations = glob(SRC . 'migrations/*.php');

    foreach ($migrations as $migration) {
        ['up' => $up, 'down' => $down] = require_once $migration;
        $migrationName = basename($migration);
        switch ($direction) {
            case 'up':
                if (!db_exists('SELECT * FROM migrations WHERE migration = ?', $migrationName)) {
                    echo "Migrating: $migrationName...\n";
                    $up();
                    db('INSERT INTO migrations (migration) VALUES (?)', $migrationName);
                    echo "Migrated: $migrationName\n\n";
                }
                break;
            case 'down':
                if (db_exists('SELECT * FROM migrations WHERE migration = ?', $migrationName)) {
                    echo "Rolling back: $migrationName...\n";
                    $down();
                    db('DELETE FROM migrations WHERE migration = ?', $migrationName);
                    echo "Rolled back: $migrationName\n\n";
                }
                break;
            default:
                throw new Exception('Invalid migration direction');
        }
    }
}

/**
 * @return void
 * @throws Exception
 */
function migrate_roll_back(): void
{
    migrate('down');
}

function make_migration(string $name): void
{
    if (!$name) {
        throw new Exception('Please provide a name for the migration');
    }

    $migration = date('YmdHis') . '_' . $name . '.php';
    $path = SRC . 'migrations/' . $migration;
    file_put_contents($path,
<<<PHP
    <?php

        return [
            'up' => function() {
                // Write your migration code here
            },
            'down' => function() {
                // Write your migration code here
            }
        ];
PHP);

    echo "Created migration: $migration\n";
}