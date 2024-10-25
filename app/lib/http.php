<?php

function response(string|Closure|null $response = null, int $status = 200)
{
    http_response_code($status);

    if  (is_callable($response)) {
        return $response();
    }

    return $response;
}

function expect_request_method(string|array $method): void
{
    if ($method === '*') {
        return;
    }

    if (!in_array($_SERVER['REQUEST_METHOD'], (array) $method)) {
        throw new Exception('Invalid request method');
    }
}

function redirect(string $url): void
{
    http_response_code(302);
    header("Location: $url");
    exit;
}