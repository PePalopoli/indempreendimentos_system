<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

use Symfony\Component\Console\Application;
use Palopoli\PaloSystem\Command as Commands;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Tools\Console\ConsoleRunner;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;

$console = new Application('PaloSystem', $app['composer']['version']);

$app->boot();

$console->add(new Commands\GeneratorCommand($app));
$console->add(new Commands\UserCreateCommand($app));
$console->add(new Commands\ServerCommand($app));
$console->add(new Commands\RouterListCommand($app));
$console->add(new Commands\MakeControllerCommand($app));

/*
 * Doctrine CLI
 *
 * https://github.com/dflydev/dflydev-doctrine-orm-service-provider/issues/11
 */
$helperSet = new HelperSet(array(
    // DBAL Commands
    'db' => new ConnectionHelper($app['db']),
));

$console->setHelperSet($helperSet);
ConsoleRunner::addCommands($console);

return $console;
