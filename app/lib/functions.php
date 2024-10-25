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