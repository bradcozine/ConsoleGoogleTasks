#!/usr/bin/env php
<?php

/**
 * @link https://developers.google.com/google-apps/tasks/v1/reference/
 * @link https://developers.google.com/google-apps/tasks/quickstart/php#step_2_install_the_google_client_library
 */

if (file_exists(__DIR__.'/../../../autoload.php')) {
    // Include global autoload if app is installed with Composer
    require __DIR__.'/../../../autoload.php';
} elseif (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
} else {
    throw new \Exception('Unable to find Composer autoload file: Install Composer dependencies first.');
}

use AlVi\Application;
use Pimple\Container;

$container = new Container();
$container['google_client'] = function () {
    $client = new Google_Client();

    // configure google client
    $client->setApplicationName('Console Google Tasks');
    $client->setAccessType('offline');
    $client->setAuthConfigFile(__DIR__.'/../client_secret.json');
    $client->setScopes([
        \Google_Service_Tasks::TASKS,
        \Google_Service_Tasks::TASKS_READONLY,
    ]);

    return $client;
};
$container['google_service_tasks'] = function (Container $c) {
    return new Google_Service_Tasks($c['google_client']);
};

$app = new Application();
$app->setContainer($container);

// Register commands
$app->addCommands([
    new \AlVi\Command\App\ConfigureCommand(),
    new \AlVi\Command\Task\ClearCommand(),
    new \AlVi\Command\Task\CompleteCommand(),
    new \AlVi\Command\Task\CreateCommand(),
    new \AlVi\Command\Task\DeleteCommand(),
    new \AlVi\Command\Task\IncompleteCommand(),
    new \AlVi\Command\Task\ListCommand(),
    new \AlVi\Command\Task\RenameCommand(),
    new \AlVi\Command\Task\ShowCommand(),
    new \AlVi\Command\TaskList\CreateCommand(),
    new \AlVi\Command\TaskList\DeleteCommand(),
    new \AlVi\Command\TaskList\ListCommand(),
    new \AlVi\Command\TaskList\RenameCommand(),
    new \AlVi\Command\TaskList\ShowCommand(),
]);

$app->run();
