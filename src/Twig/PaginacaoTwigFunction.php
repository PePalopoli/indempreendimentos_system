<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Twig;

/**
 * Class AssetTwigFunction.
 *
 * http://twig.sensiolabs.org/doc/advanced.html#creating-an-extension
 */
class PaginacaoTwigFunction extends TwigContainerAware
{
    public function getName()
    {
        return 'paginacao';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getPages', array($this, 'getPages')),            
            new \Twig_SimpleFunction('getArrayPages', array($this, 'getArrayPages')),            
            new \Twig_SimpleFunction('getPagesInicial', array($this, 'getPagesInicial')),            
            new \Twig_SimpleFunction('getPagesFinal', array($this, 'getPagesFinal')),            
        );
    }

    
    private function getPages($array,$quantity)
    {
        return ceil(count($array) / $quantity);                
    }

    public function getPagesInicial($array,$pagina,$range,$quantity)
    {
        $ini = $pagina - $range;
        $ini = $ini < 1?1:$ini;
        return $ini;
    }

    public function getPagesFinal($array,$pagina,$range,$quantity)
    {
        $fim = $pagina + $range;
        $fim = $this->getPages($array,$quantity) >$fim?$fim: $this->getPages($array,$quantity);
        return $fim;
    }

    public function getArrayPages($array,$quantity,$indice)
    {
        $indice = $indice -1;
        $count = $quantity * $indice;        
        return array_slice($array,$count,$quantity);
    }
}
