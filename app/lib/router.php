<?php

/**
 * Route dispatcher
 */
function route(string $path, array $routes)
{
    ob_start();

    $found = array_filter($routes, function (string $route) use ($path) {
        [$route, $method] = explode('@', $route, 2) + [null, 'get'];
        $methods = explode('|', mb_strtoupper($method));
        return preg_match('#^' . $route . '$#', $path) && ($method === '*' || in_array($_SERVER['REQUEST_METHOD'], $methods));
    }, ARRAY_FILTER_USE_KEY);

    $pattern = array_keys($found)[0] ?? null;
    $route = $found[$pattern] ?? null;

    if (!$route) {
        return call_user_func($routes['/404']);
    }
    preg_match('#' . $pattern . '#', $path, $uriParams);
    array_shift($uriParams);

    if ($route instanceof Closure) {
        return $route($uriParams);
    }

    if (is_array($route)) {
        [$file, $fn] = $route;
        require $file;
        return $fn($uriParams);
    }
}