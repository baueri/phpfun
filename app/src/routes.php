<?php

require_once __DIR__ . '/../lib/view.php';
require_once __DIR__ . '/../lib/http.php';

return [
    '/' => fn () => require 'controller/home.php',
    '/user/(?P<user>\d+)' => [SRC . 'controller/user.php', 'user'],
    '/user/list' => [SRC . 'controller/user.php', 'user_list'],
    '/user/create' => [SRC . 'controller/user.php', 'user_create_form'],
    '/user/create@post' => [SRC . 'controller/user.php', 'user_create_user'],
    '/about' => fn () => response(view('about.php', ['name' => $_REQUEST['name'] ?? 'Nobody'])),
    '/404' => fn () => response('<h1>404</h1>', 404)
];