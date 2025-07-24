<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Twig;

/**
 * Class AssetTwigFunction.
 *
 * http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension
 */
class SecurityTwigFunction extends TwigContainerAware
{
    public function getName()
    {
        return 'security';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_granted', array($this, 'isGranted')),
        );
    }

    public function isGranted($role, $object = null)
    {
        return $this->get('security')->isGranted($role,$object);
    }
}
