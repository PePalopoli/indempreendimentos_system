<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Provider;

use Silex\Application;
use Silex\Provider\SwiftmailerServiceProvider as BaseSwiftmailerServiceProvider;

/**
 * Class SwiftmailerServiceProvider.
 *
 * http://silex.sensiolabs.org/doc/providers/swiftmailer.html
 */
class SwiftmailerServiceProvider extends BaseSwiftmailerServiceProvider
{
    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        //
        parent::register($app);

        $app['swiftmailer.use_spool'] = false;
        $app['swiftmailer.options'] = array(
            'host' => 'smtp.gmail.com',
            'port' => '587',
            'username' => 'emails.audicomunicacao@gmail.com',
            'password' => 'wqdjzolozutwnlfw',
            'from' => 'contato@booster.tech',
            'encryption' => 'tls',
            'auth_mode' => null,
        );
    }
}
