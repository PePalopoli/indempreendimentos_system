<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Palopoli\PaloSystem\Service\SeoService;


$app = new Application();

/**
 * Helpers.
 */
require_once __DIR__.'/helpers.php';

/*
 * Configurações
 */

// Url painel
$app['security_path'] = '/painel';

// Prefixo url
$app['asset_path'] = '/';

// Habilitar modo desenvolvedor
$app['debug'] = true;

// Composer configurações
$app['composer'] = json_decode(file_get_contents(base_path('composer.json')), true);

// Habilitar Http Method Override
Request::enableHttpMethodParameterOverride();

// http://silex.sensiolabs.org/doc/providers/session.html
$app->register(new Palopoli\PaloSystem\Provider\SessionServiceProvider());

// http://silex.sensiolabs.org/doc/providers/form.html
$app->register(new Palopoli\PaloSystem\Provider\FormServiceProvider());

// http://silex.sensiolabs.org/doc/providers/translation.html
$app->register(new Palopoli\PaloSystem\Provider\TranslationServiceProvider());

// http://silex.sensiolabs.org/doc/providers/validator.html
$app->register(new Palopoli\PaloSystem\Provider\ValidatorServiceProvider());

// http://silex.sensiolabs.org/doc/providers/url_generator.html
$app->register(new Palopoli\PaloSystem\Provider\UrlGeneratorServiceProvider());

// http://silex.sensiolabs.org/doc/providers/doctrine.html
$app->register(new Palopoli\PaloSystem\Provider\DoctrineServiceProvider());

// http://silex.sensiolabs.org/doc/providers/swiftmailer.html
$app->register(new Palopoli\PaloSystem\Provider\SwiftmailerServiceProvider());

// http://silex.sensiolabs.org/doc/providers/service_controller.html
$app->register(new Palopoli\PaloSystem\Provider\ServiceControllerServiceProvider());

// http://silex.sensiolabs.org/doc/providers/security.html#traits
$app->register(new Palopoli\PaloSystem\Provider\RouteProvider());

// ExceptionServiceProvider
$app->register(new Palopoli\PaloSystem\Provider\ExceptionServiceProvider());

// http://silex.sensiolabs.org/doc/providers/twig.html
$app->register(new Palopoli\PaloSystem\Provider\TwigServiceProvider());

// http://silex.sensiolabs.org/doc/providers/http_fragment.html
$app->register(new Silex\Provider\HttpFragmentServiceProvider());

// http://glide.thephpleague.com/
$app->register(new Palopoli\PaloSystem\Provider\GlideProvider());

// S.E.O. Provider
$app->register(new Palopoli\PaloSystem\Provider\SeoProvider());

// Lead Service Provider
$app->register(new Palopoli\PaloSystem\Provider\LeadServiceProvider());

// https://github.com/cocur/slugify
$app->register(new Cocur\Slugify\Bridge\Silex\SlugifyServiceProvider());

// http://silex.sensiolabs.org/doc/providers/monolog.html
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => log_path(sprintf('%s.log', (new \DateTime())->format('Y-m-d'))),
));

if ($app['debug']) {
    // https://github.com/silexphp/Silex-WebProfiler
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir' => cache_path(),
    ));
}


// http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension
$app['twig']->addExtension(new Palopoli\PaloSystem\Twig\AssetTwigFunction($app));

// https://pt.wikipedia.org/wiki/CamelCase
$app['twig']->addExtension(new Palopoli\PaloSystem\Twig\CamelizeTwigFunction($app));

// Security
$app['twig']->addExtension(new Palopoli\PaloSystem\Twig\SecurityTwigFunction($app));

// S.E.O.
$app['twig']->addExtension(new Palopoli\PaloSystem\Twig\SeoTwigFunction($app));

// Paginacao
$app['twig']->addExtension(new Palopoli\PaloSystem\Twig\PaginacaoTwigFunction($app));

// https://github.com/cocur/slugify
$app['twig']->addExtension(new Cocur\Slugify\Bridge\Twig\SlugifyExtension(Cocur\Slugify\Slugify::create()));

// http://silex.sensiolabs.org/doc/providers/security.html
$app->register(new Palopoli\PaloSystem\Provider\SecurityServiceProvider());




$app['seo'] = $app->share(function () {
    return new SeoService();
});
// $this->app['twig']->addGlobal('seo', $this->get('seo')->all());

return $app;
