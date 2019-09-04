<?php

namespace WhiteBrand;

class Template
{
    private $templateConfig;
    private $affiliateConfig;

    public function __construct ($templateConfig, $affiliateConfig)
    {
        $this->templateConfig = $templateConfig;
        $this->affiliateConfig = $affiliateConfig;
    }

    public function getHeader()
    {
        $header = file_get_contents(
            $this->templateConfig->template_folder .
            '/' .
            $this->templateConfig->template_head,
            TRUE
        );
        $header = str_replace('{{ affiliate_css }}', $this->affiliateConfig->css, $header);
        $header = str_replace('{{ affiliate_url }}', $this->affiliateConfig->url, $header);
        $header = str_replace('{{ affiliate_name }}', $this->affiliateConfig->name, $header);

        return $header;
    }

    public function getBody()
    {
        $body = file_get_contents(
            $this->templateConfig->template_folder .
            '/' .
            $this->templateConfig->template_body,
            TRUE
        );

        return $body;
    }

    public function getFooter()
    {
        $footer = file_get_contents(
            $this->templateConfig->template_folder .
            '/' .
            $this->templateConfig->template_footer,
            TRUE
        );

        return $footer;
    }
}