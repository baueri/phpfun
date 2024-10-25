<?php

require __DIR__ . '/../../app.php';

global $argv;

array_shift($argv);
$command = array_shift($argv);

$args = $argv;

require LIB . 'console/commands/' . $command . '.php';