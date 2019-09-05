<?php

namespace WhiteBrand;

class Template
{
    private $templateConfig;
    private $affiliateConfig;
    private $webcamsUrl;

    public function __construct ($templateConfig, $affiliateConfig, $webcamsUrl)
    {
        $this->templateConfig = $templateConfig;
        $this->affiliateConfig = $affiliateConfig;
        $this->webcamsUrl = $webcamsUrl;
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

        $pieceGrid = file_get_contents(
            $this->templateConfig->template_folder .
            '/' .
            $this->templateConfig->template_piece,
            TRUE
        );

        $webcamGrid = WebcamGrid::take(
            $this->webcamsUrl,
            $pieceGrid,
            $this->templateConfig,
            $this->affiliateConfig
        );

        $body = str_replace('{{ girl_list }}', $webcamGrid->makeGrid(), $body);

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

        $footer = str_replace('{{ ga_code }}', $this->affiliateConfig->ga, $footer);

        return $footer;
    }
}