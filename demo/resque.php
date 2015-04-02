<?php

require '../vendor/autoload.php';

function getQueryFile()
{
    if (empty($_SERVER['argv'][1])) {
        throw new InvalidArgumentException("Usage: php resque.php /path/to/file.sql\n");
    }

    $file = $_SERVER['argv'][1];
    if (!is_file($file)) {
        throw new InvalidArgumentException("Invalid query file\n");
    }
    return $file;
}

$queue = new Queue;

try {
    $queryFile = getQueryFile();
    $count = $queue->enqueueQueriesFromFile($queryFile, true);
    if ($count) {
        echo "Successfully added queries to the queue\n";
    } else {
        echo "No queries were found for processing\n";
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
