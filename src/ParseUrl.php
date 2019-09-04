<?php

namespace WhiteBrand;

class ParseUrl
{
    private $siteName;

    private function __construct()
    {
        $this->siteName = $_SERVER['HTTP_HOST'];
    }

    public static function getHost(): self
    {
        return new static();
    }

    public function getNameHost()
    {
        $domain = explode('.', $this->siteName);

        $tld = array_pop($domain);
        $name = array_pop($domain);

        return $name;
    }
}