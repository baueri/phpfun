<?php

require_once LIB . 'db/db.php';

/**
 * @throws Exception
 */
function migrate(string $direction = 'up'): void
{
    if (!db_table_exists('migrations')) {
        db('CREATE TABLE migrations (migration VARCHAR(255) PRIMARY KEY, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, batch INT DEFAULT 1)');
    }
    $migrations = glob(SRC . 'migrations/*.php');
    $batch = db_value('SELECT MAX(batch) FROM migrations') + 1;
    foreach ($migrations as $migration) {
        ['up' => $up, 'down' => $down] = require_once $migration;
        $migrationName = basename($migration);
        switch ($direction) {
            case 'up':
                if (!db_exists('SELECT * FROM migrations WHERE migration = ?', $migrationName)) {
                    echo "Migrating: $migrationName...\n";
                    if ($up instanceof Closure) {
                        $up();
                    } else {
                        db($up);
                    }
                    db('INSERT INTO migrations (migration, batch) VALUES (?, ?)', $migrationName, $batch);
                    echo "Migrated: $migrationName\n\n";
                }
                break;
            case 'down':
                if (db_exists('SELECT * FROM migrations WHERE migration = ? and batch = (SELECT MAX(batch) FROM migrations)', $migrationName)){
                    echo "Rolling back: $migrationName...\n";
                    if ($down instanceof Closure) {
                        $down();
                    } else {
                        db($down);
                    }
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

    $migration = date('Ymd_His') . '_' . $name . '.php';
    $path = SRC . 'migrations/' . $migration;
    preg_match('/^create_(.*)_table/', $name, $matches);

    if ($matches) {
        $table = $matches[1];
        $up = "CREATE TABLE $table (id INT AUTO_INCREMENT PRIMARY KEY)";
        $down = "DROP TABLE $table";
    } else {
        $up = $down = '';
    }

    file_put_contents($path,
<<<PHP
    <?php

        return [
            'up' => function() {
                // Write your migration code here
                db('{$up}');
            },
            'down' => function() {
                // Write your migration code here
                db('{$down}');
            }
        ];
PHP);

    echo "Created migration: $path\n";
}

function migration_get_files(): array
{
    return glob(SRC . 'migrations/*.php');
}