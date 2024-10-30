<?php

require_once SRC . '/repository/user.php';

function user(array $params): string
{
    $user = user_get_user($params['user'], [with_posts, with_roles, with_school]);

    $posts = implode(
        "\n",
        array_map(
            fn ($post) => "<li>{$post['title']}</li>",
            $user['posts']
        )
    );

    $school = $user['school']['name'] ?? 'N/A';

    return <<<HTML
        <h1>{$user['name']}</h1>
        <h3>Posts:</h3>
        <ul>
            $posts
        </ul>
        <h3>School:</h3>
        <p>$school</p>
    HTML;
}

function user_list(): string
{
    event_dispatch('user_list', ['foo' => 'bar']);

    $users = user_get_users();

    return view('user_list.php', ['users' => $users]);
}

function user_create_form(): string
{
    return view('wrapper.php', ['content' => view('edit_user.php', ['action' => '/user/create'])]);
}

function user_create_user(): void
{
    expect_request_method('POST');

    $data = $_REQUEST['user'];

    $user = user_insert_user($data);

    redirect("/user/{$user['id']}");
}