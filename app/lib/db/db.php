<?php

/**
 * Connect to the database using PDO.
 */
function db_connect(?string $connection = null): ?PDO
{
    static $connections;

    $connections ??= [];

    if (isset($connections[$connection])) {
        return $connections[$connection];
    }

    ['host' => $host, 'database' => $dbname, 'user' => $username, 'password' => $password] = cfg('db_connections')[$connection ?: 'default'];

    try {
        $connections[$connection] = $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }

    return $pdo;
}

/**
 * Execute a query (INSERT, UPDATE, DELETE).
 */
function db(string $query, ...$params): false|PDOStatement
{
    $pdo = db_connect();
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Add a WHERE condition to an array.
 */
function db_where(string $condition, mixed $value = null, string $clause = 'and', array &$where = null): void
{
    $where[] = [
        'condition' => $condition,
        'value' => $value,
        'clause' => $clause
    ];
}

/**
 * Execute a query that returns a single row.
 */
function db_row($query, ...$params): ?array
{
    return db($query, ...$params)->fetch() ?: null;
}

/**
 * Execute a query that returns multiple rows.
 */
function db_rows($query, ...$params): array
{
    return db($query, ...$params)->fetchAll();
}

/**
 * Insert a row and return the last insert ID.
 *
 * @param string $query
 * @param mixed ...$params
 * @return int Last insert ID
 */
function db_insert(string $query, ...$params): int
{
    db($query, ...$params);
    return db_connect()->lastInsertId();
}

function db_create_row(string $table, array $data): array
{
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $data['id'] = db_insert("INSERT INTO $table ($columns) VALUES ($placeholders)", ...array_values($data));

    return $data;
}

function db_value(string $query, ...$params): mixed
{
    return db($query, ...$params)->fetchColumn();
}

/**
 * Update a row if it exists; insert if it does not.
 *
 * @param string $table
 * @param array $identifier
 * @param array $data
 * @return int The number of affected rows or the last inserted ID
 */
function db_update_or_insert($table, $identifier, $data): int
{
    // Build the WHERE clause from the identifier array
    $whereClause = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($identifier)));
    $updateSet = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));

    // Build the full UPDATE and INSERT queries
    $updateQuery = "UPDATE $table SET $updateSet WHERE $whereClause";
    $insertColumns = implode(', ', array_keys(array_merge($identifier, $data)));
    $insertPlaceholders = implode(', ', array_fill(0, count(array_merge($identifier, $data)), '?'));
    $insertQuery = "INSERT INTO $table ($insertColumns) VALUES ($insertPlaceholders)";

    // Combine parameters for the queries
    $updateParams = array_values($data);
    $identifierParams = array_values($identifier);

    // Try to perform an UPDATE
    $updated = db($updateQuery, array_merge($updateParams, $identifierParams))->rowCount();

    // If no rows were updated, perform an INSERT
    if ($updated === 0) {
        db($insertQuery, array_values(array_merge($identifier, $data)));
        return db_connect()->lastInsertId();
    }

    return $updated;
}

function db_build_where(array $where): array
{
    $whereStr = '';

    foreach ($where as $i => $condition) {
        $whereStr .= ($i === 0 ? '' : $condition['clause']) . ' ' . $condition['condition'];
    }

    return [$whereStr ?: '1', array_column($where, 'value')];
}

function db_table_exists(string $table): bool
{
    return (bool) db_row('SHOW TABLES LIKE ?', $table);
}

function db_exists(string $query, ...$params): bool
{
    return (bool) db_row($query, ...$params);
}

