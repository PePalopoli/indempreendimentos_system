<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

namespace Palopoli\PaloSystem\Twig;

use Symfony\Component\HttpFoundation\Request;

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
            new \Twig_SimpleFunction('getCurrentUrl', array($this, 'getCurrentUrl')),
            new \Twig_SimpleFunction('getBannerImage', array($this, 'getBannerImage')),
            new \Twig_SimpleFunction('getThumbnailImage', array($this, 'getThumbnailImage')),
            new \Twig_SimpleFunction('getResponsiveImage', array($this, 'getResponsiveImage')),
            new \Twig_SimpleFunction('getPlaceholderImage', array($this, 'getPlaceholderImage')),
            new \Twig_SimpleFunction('getGalleryMainImage', array($this, 'getGalleryMainImage')),
            new \Twig_SimpleFunction('getGalleryThumbnail', array($this, 'getGalleryThumbnail')),
            new \Twig_SimpleFunction('getTiposEmpreendimentos', array($this, 'getTiposEmpreendimentos')),
            new \Twig_SimpleFunction('clearTiposEmpreendimentosCache', array($this, 'clearTiposEmpreendimentosCache')),
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

    /**
     * Retorna a URL atual da requisição
     */
    public function getCurrentUrl()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['HTTP_HOST'];
            return $protocol . $domainName . $_SERVER['REQUEST_URI'];
        }
        return '';
    }

    /**
     * Gera URLs otimizadas para imagens de banner com WebP
     */
    public function getBannerImage($path, $imagem, $width = 1920, $height = 500, $quality = 85)
    {
        $params = [
            'w' => $width,
            'h' => $height,
            'fit' => 'crop',
            'q' => $quality,
            'fm' => 'webp'
        ];
        
        return $this->get('url_generator')->generate('imagem', [
            'path' => $path,
            'imagem' => $imagem
        ]) . '?' . http_build_query($params);
    }

    /**
     * Gera URLs otimizadas para miniaturas de empreendimentos
     */
    public function getThumbnailImage($path, $imagem, $width = 567, $height = 400, $quality = 80)
    {
        $params = [
            'w' => $width,
            'h' => $height,
            'fit' => 'crop',
            'q' => $quality,
            'fm' => 'webp'
        ];
        
        return $this->get('url_generator')->generate('imagem', [
            'path' => $path,
            'imagem' => $imagem
        ]) . '?' . http_build_query($params);
    }

    /**
     * Gera srcset responsivo para imagens
     */
    public function getResponsiveImage($path, $imagem, $baseWidth = 567, $baseHeight = 400)
    {
        $sizes = [
            ['w' => intval($baseWidth * 0.5), 'h' => intval($baseHeight * 0.5), 'descriptor' => '480w'],
            ['w' => intval($baseWidth * 0.75), 'h' => intval($baseHeight * 0.75), 'descriptor' => '768w'],
            ['w' => $baseWidth, 'h' => $baseHeight, 'descriptor' => '1024w'],
            ['w' => intval($baseWidth * 1.5), 'h' => intval($baseHeight * 1.5), 'descriptor' => '1440w']
        ];
        
        $srcset = [];
        foreach ($sizes as $size) {
            $params = [
                'w' => $size['w'],
                'h' => $size['h'],
                'fit' => 'crop',
                'q' => 80,
                'fm' => 'webp'
            ];
            
            $url = $this->get('url_generator')->generate('imagem', [
                'path' => $path,
                'imagem' => $imagem
            ]) . '?' . http_build_query($params);
            
            $srcset[] = $url . ' ' . $size['descriptor'];
        }
        
        return implode(', ', $srcset);
    }

    /**
     * Gera placeholder blur para lazy loading
     */
    public function getPlaceholderImage($path, $imagem, $width = 50, $height = 50)
    {
        $params = [
            'w' => $width,
            'h' => $height,
            'fit' => 'crop',
            'q' => 30,
            'blur' => 20,
            'fm' => 'webp'
        ];
        
        return $this->get('url_generator')->generate('imagem', [
            'path' => $path,
            'imagem' => $imagem
        ]) . '?' . http_build_query($params);
    }

    /**
     * Gera URLs otimizadas para imagem principal da galeria
     */
    public function getGalleryMainImage($path, $imagem, $width = 1200, $height = 800, $quality = 85)
    {
        $params = [
            'w' => $width,
            'h' => $height,
            'fit' => 'crop',
            'q' => $quality,
            'fm' => 'webp'
        ];
        
        return $this->get('url_generator')->generate('imagem', [
            'path' => $path,
            'imagem' => $imagem
        ]) . '?' . http_build_query($params);
    }

    /**
     * Gera URLs otimizadas para thumbnails da galeria
     */
    public function getGalleryThumbnail($path, $imagem, $width = 200, $height = 150, $quality = 80)
    {
        $params = [
            'w' => $width,
            'h' => $height,
            'fit' => 'crop',
            'q' => $quality,
            'fm' => 'webp'
        ];
        
        return $this->get('url_generator')->generate('imagem', [
            'path' => $path,
            'imagem' => $imagem
        ]) . '?' . http_build_query($params);
    }

    /**
     * Busca tipos/etapas de empreendimentos com cache
     * Cache de 1 hora para evitar múltiplas consultas ao banco
     */
    public function getTiposEmpreendimentos()
    {
        $cacheFile = sys_get_temp_dir() . '/etapas_empreendimentos_cache.json';
        $cacheTime = 3600; // 1 hora

        // Verificar se cache existe e não expirou
        if (file_exists($cacheFile)) {
            $cacheData = json_decode(file_get_contents($cacheFile), true);
            
            if ($cacheData && isset($cacheData['timestamp']) && (time() - $cacheData['timestamp']) < $cacheTime) {
                return $cacheData['data'];
            }
        }

        try {
            // Buscar dados do banco
            $etapas = $this->get('db')->fetchAll("SELECT id, titulo, cor_hex, slug FROM obra_etapas WHERE enabled = 1 ORDER BY `order` ASC");
            
            // Salvar no cache
            $cacheContent = [
                'timestamp' => time(),
                'data' => $etapas
            ];
            
            file_put_contents($cacheFile, json_encode($cacheContent));
            
            return $etapas;
            
        } catch (\Exception $e) {
            // Em caso de erro, retornar cache antigo se existir ou array vazio
            if (file_exists($cacheFile)) {
                $cacheData = json_decode(file_get_contents($cacheFile), true);
                return $cacheData['data'] ?? [];
            }
            
            // Log do erro para debug
            error_log("Erro ao buscar etapas de empreendimentos: " . $e->getMessage());
            
            return [];
        }
    }

    /**
     * Limpa o cache de tipos de empreendimentos
     * Útil para usar após adicionar/editar etapas no admin
     */
    public function clearTiposEmpreendimentosCache()
    {
        $cacheFile = sys_get_temp_dir() . '/etapas_empreendimentos_cache.json';
        
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
        
        return true;
    }
    
}
