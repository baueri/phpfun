<?php
require __DIR__ . '/../console.php';
require LIB . 'db/migration.php';

[$direction] = $args = console_get_args() + [null];

switch ($direction) {
    case 'up':
    case null:
        migrate();
        break;
    case 'down':
        migrate_roll_back();
        break;
    case 'make':
        make_migration($args[1] ?? '');
        break;
    default:
        throw new Exception('Invalid command');
}