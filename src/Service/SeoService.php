<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */

namespace Palopoli\PaloSystem\Service;

class SeoService
{
    protected $data = [];

    public function setTitle($title) { $this->data['title'] = $title; return $this; }
    public function setDescription($desc) { $this->data['description'] = $desc; return $this; }
    public function setKeywords($kw) { $this->data['keywords'] = $kw; return $this; }
    public function setRobots($rb) { $this->data['robots'] = $rb; return $this; }
    public function setCanonical($url) { $this->data['canonical'] = $url; return $this; }
    public function setImage($img) { $this->data['image'] = $img; return $this; }
    public function setTwitterCard($card) { $this->data['twitter_card'] = $card; return $this; }
    public function setTwitterImage($img) { $this->data['twitter_image'] = $img; return $this; }

    public function all(): array { return $this->data; }
}
