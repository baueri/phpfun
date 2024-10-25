<?php

function event(string|callable $action, $event = null, $payload = null)
{
    static $events;
    $events ??= [];

    $listen = function () use (&$events, $event, $payload) {
        if (!isset($events[$event])) {
            $events[$event] = [];
        }
        $events[$event][] = $payload;
    };

    $dispatch = function () use ($events, $event, $payload) {
        foreach ($events[$event] ?? [] as $callback) {
            $callback($payload);
        }
    };

    switch ($action) {
        case 'listen':
            return $listen();
        case 'dispatch':
            return $dispatch();
    }
}

function event_distpatch(string $event, $payload = null): void
{
    event('dispatch', $event, $payload);
}

function event_listen(string $event, callable $callback): void
{
    event('listen', $event, $callback);
}