<?php

require __DIR__ . '/../vendor/autoload.php';

$queue = new Queue;

try {
    $queue->enqueueQueriesFromFile(__DIR__ . '/sql/queries.sql');
} catch (Exception $e) {
    echo $e->getMessage();
}
