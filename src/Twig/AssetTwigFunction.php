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
class AssetTwigFunction extends TwigContainerAware
{
    public function getName()
    {
        return 'asset';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('asset', array($this, 'find')),
            new \Twig_SimpleFunction('format_date', array($this, 'format_date')),
            new \Twig_SimpleFunction('formatCSRF', array($this, 'formatCSRF')),
            new \Twig_SimpleFunction('getUrlBoleto', array($this, 'getUrlBoleto')),
            new \Twig_SimpleFunction('getUrlSistema', array($this, 'getUrlSistema')),
        );
    }

    public function find($asset)
    {
        $request = $this->get('request');
        $url = '';
        if ($request instanceof Request) {
            $url = $request->getBaseUrl();
        }

        try {
            $parameters = $this->get('composer');

            $version = $parameters['version'];
        } catch (\InvalidArgumentException $e) {
            $version = '0.0.1';
        }

        if ($request->server->get('HTTP_HOST') === 'localhost') {
            $url = substr($request->server->get('SCRIPT_NAME'), 0, -10).$url;
        }

        return sprintf('%s/%s%sv=%s', $url, $asset, strpos($asset, '?') === false ? '?' : '&', $version);
    }

    public function format_date($data){
        $date = date_create($data);
        $date_exibe = date_format($date, 'd/m/Y');        
        return $date_exibe;
    }


    public function formatCSRF()
    {
        // Chave secreta do servidor (coloque em um local seguro, ex: .env)
        $secret = 'e3b0c44298fc1c149afbboc8996fb92427aa45e4167b934ca495991b7852b855';

        // Pode adicionar mais dados, como IP, user-agent, etc.
        $data = time(); // timestamp atual
        $token = base64_encode($data . ':' . hash_hmac('sha256', $data, $secret));
        return $token;
    }
    public function getUrlBoleto()
    {        
        return "https://fidelixadvogados.com.br/boletos/index.php";
    }
    public function getUrlSistema()
    {        
        return "https://indempreendimentos.cvcrm.com.br/cliente";
    }
    
}
