<?php

class Queue
{
    const QUERIES_SEPARATOR = "\n\n";

    /**
     * @var array
     */
    public $queriesPatterns = array();

    public function __construct($redisBackend = 'localhost:6379')
    {
        Resque::setBackend($redisBackend);
        $this->queriesPatterns = array(
            '/CREATE INDEX `\w+` ON `(\w+)`/',
            '/DROP INDEX `\w+` ON `(\w+)`/',
            '/CREATE TABLE `(\w+)`/',
            '/DROP TABLE `(\w+)`/',
            '/ALTER TABLE `(\w+)`/',
            '/TRUNCATE TABLE `(\w+)`/',
        );
    }

    /**
     * @param $query
     * @return string|bool
     */
    public function matchTable($query)
    {
        foreach ($this->queriesPatterns as $pattern) {
            if (preg_match($pattern, $query, $matches) && isset($matches[1])) {
                return $matches[1];
            }
        }
        return false;
    }

    /**
     * @param $dataSource
     * @param string $separator
     * @return array
     */
    public function getGroupedQueries($dataSource, $separator = self::QUERIES_SEPARATOR)
    {
        $queries = array();
        $rawData = explode($separator, $dataSource);

        foreach ($rawData as $query) {
            if (empty($query)) {
                continue;
            }

            $table = $this->matchTable($query);
            if (!$table) {
                continue;
            }

            if (!isset($queries[$table])) {
                $queries[$table] = $query;
            } else {
                $queries[$table] .= "\n" . $query;
            }
        }

        return $queries;
    }

    /**
     * @param $queriesFile
     * @param bool $groupByTable
     */
    public function enqueueQueriesFromFile($queriesFile, $groupByTable = false)
    {
        if ($groupByTable) {
            $queries = $this->getGroupedQueries(file_get_contents($queriesFile));
        } else {
            $queries = array(file_get_contents($queriesFile));
        }

        foreach ($queries as $query) {
            Resque::enqueue('upgrade', 'Job', ['q' => $query]);
        }
    }
}
