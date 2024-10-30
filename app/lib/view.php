<?php

function view(string $file, array $args = []): string
{
    ob_start();

    extract($args);

    require ROOT . 'src/view/' . $file;

    return ob_get_clean();
}

function inject_into(string $destination)
{

}