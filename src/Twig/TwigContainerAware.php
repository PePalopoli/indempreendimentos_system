<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Twig;

use Silex\Application;
use Palopoli\PaloSystem\Traits\ContainerTrait;

/**
 * Class TwigContainerAware.
 *
 * http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension
 */
abstract class TwigContainerAware extends \Twig_Extension
{
    use ContainerTrait;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->setContainer($app);
    }
}
