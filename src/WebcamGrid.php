<?php

namespace WhiteBrand;

use Memcached;

class WebcamGrid
{
    private $jsonUrl;
    private $pieceHtml;
    private $templateConfig;
    private $affiliateConfig;
    private $memcached;

    private function __construct(string $url, string $pieceHtml, $templateConfig, $affiliateConfig)
    {
        $this->jsonUrl = $url;
        $this->pieceHtml = $pieceHtml;
        $this->templateConfig = $templateConfig;
        $this->affiliateConfig = $affiliateConfig;

        $this->memcached = new Memcached();
        $this->memcached->addServer('127.0.0.1', 11211) or die ("Unable to connect to Memcached");
    }

    public static function take(string $url, string $pieceHtml, $templateConfig, $affiliateConfig): self
    {
        return new static($url, $pieceHtml, $templateConfig, $affiliateConfig);
    }

    private function getJson()
    {
        return json_decode(JsonFile::openJson($this->jsonUrl)->getContent());
    }

    private function mountGridElement($class, $permalink, $image, $nick, $width, $height)
    {
        $urlJoin = $this->templateConfig->url_join . '{{ permalink }}/?nats=' . $this->affiliateConfig->nats_webcam;
        $urlJoin = str_replace('{{ permalink }}', $permalink, $urlJoin);

        $urlImage = $this->templateConfig->image_source . $image;

        $gridElement = $this->pieceHtml;
        $gridElement = str_replace('{{ special_class }}', $class, $gridElement);
        $gridElement = str_replace('{{ url_girl }}', $urlJoin, $gridElement);
        $gridElement = str_replace('{{ image_girl }}', $urlImage, $gridElement);
        $gridElement = str_replace('{{ name_girl }}', $nick, $gridElement);
        $gridElement = str_replace('{{ width }}', $width, $gridElement);
        $gridElement = str_replace('{{ height }}', $height, $gridElement);
        return $gridElement;
    }

    private function getGridCache($key)
    {
        return $this->memcached->get($key);
    }

    private function setGridCache($key, $value, $expire = 900)
    {
        return $this->memcached->set($key, $value, $expire) or die ("Unable to set to Memcached");
    }

    public function makeGrid(): string
    {
        $gridContent = $this->getGridCache("gridContent");
        if ($gridContent)
        {
            return $gridContent;
        }

        $allWebcams = $this->getJson();

        $noClass = "";
        $bigThumbsCount = 0;
        $arrayBigThumbs = array();
        $arrayThumbs = array();

        $specialThumb = array_shift($allWebcams);
        foreach ($allWebcams as $webcam) {
            if ($bigThumbsCount < 4) {
                $bigThumbsCount++;
                $arrayBigThumbs[] = $webcam;
            }
            $arrayThumbs[] = $webcam;
        }

        $specialBig = false;
        $bigThumbsCount = 0;
        $bigRight = false;
        $lineCount = 0;
        $gridContent = "";

        foreach ($arrayThumbs as $thumb) {
            // First five
            if ($lineCount < 5) {
                $lineCount++;
                $gridContent = $gridContent .
                               $this->mountGridElement(
                                    $noClass,
                                    $thumb->wbmerPermalink,
                                    $thumb->wbmerThumb2,
                                    $thumb->wbmerNick,
                                    $this->templateConfig->normal_width,
                                    $this->templateConfig->normal_height
                               );
                continue;
            }
            // Big image
            if ($lineCount == 5) {
                $lineCount++;
                if (!$bigRight) {
                    $bigRight = true;
                    $bigClass = "chica-grande";
                } else {
                    $bigRight = false;
                    $bigClass = "chica-grande grande-derecha";
                }
                if (!$specialBig) {
                    $specialBig = true;
                    $gridContent = $gridContent .
                                   $this->mountGridElement(
                                        $bigClass,
                                        $specialThumb->wbmerPermalink,
                                        $specialThumb->wbmerThumb3,
                                        $specialThumb->wbmerNick,
                                        $this->templateConfig->big_width,
                                        $this->templateConfig->big_height
                        );
                } else {
                    $gridContent = $gridContent .
                                   $this->mountGridElement(
                                        $bigClass,
                                        $arrayBigThumbs[$bigThumbsCount]->wbmerPermalink,
                                        $arrayBigThumbs[$bigThumbsCount]->wbmerThumb3,
                                        $arrayBigThumbs[$bigThumbsCount]->wbmerNick,
                                        $this->templateConfig->big_width,
                                        $this->templateConfig->big_height
                                    );
                    $bigThumbsCount++;
                }
                $lineCount++;
                $gridContent = $gridContent .
                               $this->mountGridElement(
                                    $noClass,
                                    $thumb->wbmerPermalink,
                                    $thumb->wbmerThumb2,
                                    $thumb->wbmerNick,
                                    $this->templateConfig->normal_width,
                                    $this->templateConfig->normal_height
                               );
                continue;
            }
            // Lasts six
            if ($lineCount < 12) {
                $lineCount++;
                $gridContent = $gridContent .
                               $this->mountGridElement(
                                    $noClass,
                                    $thumb->wbmerPermalink,
                                    $thumb->wbmerThumb2,
                                    $thumb->wbmerNick,
                                    $this->templateConfig->normal_width,
                                    $this->templateConfig->normal_height
                               );
                continue;
            }
            $lineCount = 0;
        }

        $this->setGridCache("gridContent", $gridContent, 900);
        return $gridContent;
    }
}