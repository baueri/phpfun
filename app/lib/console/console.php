<?php

require __DIR__ . '/../../app.php';

function console_get_args(): array
{
    global $argv;
    $args = $argv;
    array_shift($args);

    return $args;
}
