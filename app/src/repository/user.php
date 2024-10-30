<?php

require_once LIB . 'db/db.php';

const with_posts = 'has_many:posts(author,id)';
const with_roles = 'has_many_through:roles(role_id,id)->user_roles(user_id,id)'; // @TODO: Implement has_many_through
const with_school = 'belongs_to:schools(school_id,id,school)';
//const with_posts = 'has_many:posts(posts.author=users.id)';
//const with_posts_v2 = 'has_many:posts(author,id)';
//const with_posts_and_comments = with_posts . '|has_many:comments(comments.post=posts.id)';

function user_get_users($where = []): array
{
    [$where, $bindings] = db_build_where($where);
    return db_rows(sprintf('SELECT * FROM user WHERE %s', $where), ...$bindings);
}

function user_get_user($id, array|string $relations = []): ?array
{
    db_where('id=?', $id, where: $w);
    [$where, $bindings] = db_build_where($w);

    $row = db_row(sprintf('SELECT * FROM user WHERE %s', $where), ...$bindings);

    foreach ((array) $relations as $relation) {
        preg_match('/^(?P<type>[a-z_]+):(?P<table>[a-zA-Z0-9\-_]+)\((?P<keys>.*)\)/', $relation, $matches);
        ['type' => $type, 'table' => $table, 'keys' => $keys] = $matches;
        [$first, $second, $relation_name] = explode(',', $keys) + [null, null, null];
        $relation_name = $relation_name ?: $table;
        switch ($type) {
            case 'has_many':
                $row[$relation_name] = db_rows("SELECT * FROM $table WHERE {$first}=?", $row[$second]);
                break;
            case 'belongs_to':
                $row[$relation_name] = db_row("SELECT * FROM $table WHERE {$second}=?", $row[$first]);
                break;
            case 'has_many_through':
//                [$pivot, $pivotKey, $key] = explode('->', $second);
                log_dump('TODO: Implement has_many_through');
//                $row[$table] = db_rows(
//                    "SELECT * FROM $table WHERE id IN (SELECT $key FROM $pivot WHERE $pivotKey=?)",
//                    $row[$first]
//                );
                break;
        }
    }

    return $row;
}

function user_insert_user(array $data): array
{
    return db_create_row('user', $data);
}

function user_authenticate(string $email, string $password): ?array
{
    $user = db_row('SELECT * FROM user WHERE email=?', $email);

    if (!$user) {
        return null;
    }

    if (!password_verify($password, $user['password'])) {
        return null;
    }

    return $user;
}