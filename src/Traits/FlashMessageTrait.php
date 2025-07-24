<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Traits;

trait FlashMessageTrait
{
    /**
     * Retorna Flash message.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBag
     */
    protected function flashMessage()
    {
        return $this->get('session')->getFlashBag();
    }
}
