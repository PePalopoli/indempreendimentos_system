<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Command;

use Symfony\Component\Console\Command\Command;
use Silex\Application;
use Palopoli\PaloSystem\Traits\ContainerTrait;

/**
 * Class AbstractCommand.
 */
abstract class AbstractCommand extends Command
{
    use ContainerTrait;

    /**
     * @param Application $app
     */
    public function __construct(Application $app = null, $name = null)
    {
        if (null !== $app) {
            $this->setContainer($app);
        }
        parent::__construct($name);
    }
}
