<?php

require_once LIB . 'db/db.php';

function user_get_users($where = []): array
{
    [$where, $bindings] = db_build_where($where);
    return db_rows(sprintf('SELECT * FROM user WHERE %s', $where), ...$bindings);
}

function user_get_user($id): ?array
{
    db_where('id=?', $id, where: $w);
    [$where, $bindings] = db_build_where($w);

    return db_row(sprintf('SELECT * FROM user WHERE %s', $where), ...$bindings);
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