<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Provider;

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider as BaseDoctrineServiceProvider;

/**
 * Class DoctrineServiceProvider.
 *
 * http://silex.sensiolabs.org/doc/providers/doctrine.html
 */
class DoctrineServiceProvider extends BaseDoctrineServiceProvider
{
    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['dbs.options'] = array(
            'db' => array(
                'driver' => 'pdo_mysql',
                'dbname' => getenv('DATABASE_NAME') ?: 'indempreendimentos',
                'host' => getenv('DATABASE_HOST') ?: 'localhost',
                'user' => getenv('DATABASE_USER') ?: 'root',
                'password' => getenv('DATABASE_PASS') ?: '',
                'charset' => 'utf8',
            ),
        );

        //
        parent::register($app);
    }
}
