<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */
namespace Palopoli\PaloSystem\Service;

use Silex\Application;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BannerTypeConverter
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function convert($id)
    {
        $type = $this->app['db']->fetchAssoc('SELECT * FROM `banner_type` WHERE `id` = ?', array($id));

        if (!is_array($type)) {
            throw new NotFoundHttpException(sprintf('Banner type %d does not exist', $id));
        }

        return $type;
    }
}
