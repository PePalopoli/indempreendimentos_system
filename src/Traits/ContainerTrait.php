<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Traits;

use Silex\Application;

trait ContainerTrait
{
    /**
     * @var Application
     */
    private $app;

    /**
     * Setar Application.
     *
     * @param Application $app
     */
    public function setContainer(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Retorna Application.
     *
     * @return Application
     */
    protected function getContainer()
    {
        return $this->app;
    }

    /**
     * Retorna serviço.
     *
     * @param $service
     *
     * @return mixed
     */
    protected function get($service)
    {
        return $this->getContainer()[$service];
    }
}
