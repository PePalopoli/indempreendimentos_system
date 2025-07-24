<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

if (!ini_get('date.timezone')) {
    date_default_timezone_set('America/Sao_Paulo');
}

$app = require_once __DIR__.'/bootstrap.php';

$app->mount('/', require_once __DIR__.'/routes.php');

return $app;
