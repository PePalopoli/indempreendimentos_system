<?php

namespace Palopoli\PaloSystem\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Palopoli\PaloSystem\Service\LeadService;
use Palopoli\PaloSystem\Service\CsrfService;
use Palopoli\PaloSystem\Service\SimpleCsrfService;
use Palopoli\PaloSystem\Service\UltraSimpleCsrf;
use Palopoli\PaloSystem\Service\DirectCsrf;

class LeadServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        // Registrar LeadService
        $app['lead.service'] = $app->share(function ($app) {
            return new LeadService($app['db']);
        });

        // Registrar DirectCsrf (versão mais direta possível)
        $app['csrf.service'] = $app->share(function ($app) {
            return new DirectCsrf();
        });

        // Registrar função Twig para gerar token CSRF
        $app->extend('twig', function ($twig, $app) {
            $twig->addFunction(new \Twig_SimpleFunction('csrf_token', function ($formType = 'default') use ($app) {
                return $app['csrf.service']->generateToken($formType);
            }));

            // Adicionar função para pegar URL atual
            // $twig->addFunction(new \Twig_SimpleFunction('getCurrentUrl', function () use ($app) {
            //     return $app['asset_function']->getCurrentUrl();
            // }));

            return $twig;
        });
    }

    public function boot(Application $app)
    {
        // Não é necessário nenhuma configuração adicional no boot
    }
}