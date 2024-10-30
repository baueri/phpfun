<?php

function dump(...$arr): void
{
    if(!is_cli())
        echo '<pre>';

    var_dump(...$arr);

    if(!is_cli())
        echo '</pre>';
}

function dd(): never
{
    dump(...func_get_args());
    exit(0);
}

function cfg(string $key)
{
    static $cfg;

    $cfg ??= require_once SRC . 'config.php';

    return $key ? $cfg[$key] ?? null : $cfg;
}

function is_cli(): bool
{
    return PHP_SAPI === 'cli';
}

function log_dump(...$arr): void
{
    $log = '[' . date('Y-m-d H:i:s') . '] - ';
    foreach ($arr as $item) {
        $log .= print_r($item, true) . PHP_EOL;
    }
    file_put_contents(STORAGE . 'fun.log', $log, FILE_APPEND);
}