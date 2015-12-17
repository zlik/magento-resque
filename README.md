# magento-resque

The main idea for the fast Magento database upgrade was to run SQL queries in parallel. This approach first introduced in [Magento Upgrade Replay](https://github.com/magento-ecg/magento-upgrade-replay) project.

Magento Resque is an utility intended to be used as an extendible standalone solution for SQL queries paralell execution during Magento upgrade.

It's nothing more than a tiny wrapper around [PHP Resque](https://github.com/chrisboulton/php-resque) – a Radis-backed library for creating jobs, placing them into a queue and processing by workers. Please reffer to https://github.com/chrisboulton/php-resque for detailed documentation.

Magento Resque basically implements two classes:

*Queue* – reads SQL queries from the specified file, wraps them into Resque jobs and enqueues the jobs in the Redis queue.

*Job* – run by workers. Instantiates Magento DB adapter and runs SQL queries wrapped into the job.

## Installation

The easiest way to install Magento Resque is using [Composer](http://getcomposer.org/).

Create `shell/magento-resque/composer.json` file under Magento root directory.

Include Magento Resque to your project:

```
composer require zlik/magento-resque
```
Run `composer install`.

## Usage Example

Change the current working directory to `demo`.

To enqueue SQL queries from the `sql/queries.sql` file to the `upgrade` queue run the following command:
```
$ php resque.php sql/queries.sql
```
To launch eight simultaneous workers:
```
$ QUEUE='upgrade' COUNT=8 ../vendor/bin/resque
```
The result should be like this:
```
[notice] Starting work on (Job{upgrade} | ID: 1 | Job | [{"q":"CREATE TABLE `resque_test_1` ..."}])
[notice] Starting work on (Job{upgrade} | ID: 2 | Job | [{"q":"CREATE TABLE `resque_test_2` ..."}])
[notice] (Job{upgrade} | ID: 2 | Job | [{"q":"CREATE TABLE `resque_test_2` ..."}]) has finished
[notice] (Job{upgrade} | ID: 1 | Job | [{"q":"CREATE TABLE `resque_test_1` ..."}]) has finished

```

##Requirements

PHP Redis requires

* PHP 5.3+
* Redis 2.2+
