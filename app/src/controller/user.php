<?php

require_once SRC . '/repository/user.php';

function user(array $params): string
{
    $user = user_get_user($params['user']);
    return <<<HTML
        <h1>{$user['name']}</h1>
    HTML;
}

function user_list(): string
{
    event_distpatch('user_list', ['foo' => 'bar']);

    $users = user_get_users();

    return view('user_list.php', ['users' => $users]);
}

function user_create_form(): string
{
    return view('edit_user.php', ['action' => '/user/create']);
}

function user_create_user(): void
{
    expect_request_method('POST');

    $data = $_REQUEST['user'];

    $user = user_insert_user($data);

    redirect("/user/{$user['id']}");
}