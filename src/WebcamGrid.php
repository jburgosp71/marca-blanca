<?php

namespace WhiteBrand;

class WebcamGrid
{
    private $jsonUrl;
    private $pieceHtml;
    private $template_config;

    private function __construct(string $url, string $pieceHtml, $template_config)
    {
        $this->jsonUrl = $url;
        $this->pieceHtml = $pieceHtml;
        $this->template_config = $template_config;
    }

    public static function take(string $url, string $pieceHtml): self
    {
        return new static($url, $pieceHtml);
    }

    private function getJson()
    {
        return json_decode(JsonFile::openJson($this->jsonUrl)->getContent());
    }

    public function makeGrid()
    {
        $url = "http://webcams.cumlouder.com/feed/webcams/online/61/1/";
        $urlJoin = "http://webcams.cumlouder.com/joinmb/cumlouder/";
        $allWebcams = json_decode(file_get_contents($url, TRUE));

        $specialBig = false;
        $bigThumbsCount = 0;
        $arrayBigThumbs = array();
        $arrayThumbs = array();
        $gridCams = "";

        foreach ($allWebcams as $webcam) {
            if (!$specialBig) {
                $specialBig = true;
                $specialThumb = $webcam;
                continue;
            }
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
        $gridElement = "";
        foreach ($arrayThumbs as $thumb) {
            // First five
            if ($lineCount < 5) {
                $gridElement = $piece;
                $lineCount++;
                $gridElement = str_replace('{{ special_class }}', '', $gridElement);
                $gridElement = str_replace('{{ url_girl }}', $urlJoin . $thumb->wbmerPermalink, $gridElement);
                $gridElement = str_replace('{{ image_girl }}', $thumb->wbmerThumb2, $gridElement);
                $gridElement = str_replace('{{ name_girl }}', $thumb->wbmerNick, $gridElement);
                $gridElement = str_replace('{{ width }}', 175, $gridElement);
                $gridElement = str_replace('{{ height }}', 150, $gridElement);
                $gridContent = $gridContent . $gridElement;
                continue;
            }
            // Big image
            if ($lineCount == 5) {
                $gridElement = $piece;
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
                    $gridElement = str_replace('{{ special_class }}', $bigClass, $gridElement);
                    $gridElement = str_replace('{{ url_girl }}', $urlJoin . $specialThumb->wbmerPermalink, $gridElement);
                    $gridElement = str_replace('{{ image_girl }}', $specialThumb->wbmerThumb2, $gridElement);
                    $gridElement = str_replace('{{ name_girl }}', $specialThumb->wbmerNick, $gridElement);
                    $gridElement = str_replace('{{ width }}', 337, $gridElement);
                    $gridElement = str_replace('{{ height }}', 307, $gridElement);
                    $gridContent = $gridContent . $gridElement;
                } else {
                    $gridElement = str_replace('{{ special_class }}', $bigClass, $gridElement);
                    $gridElement = str_replace('{{ url_girl }}', $urlJoin . $arrayBigThumbs[$bigThumbsCount]->wbmerPermalink, $gridElement);
                    $gridElement = str_replace('{{ image_girl }}', $arrayBigThumbs[$bigThumbsCount]->wbmerThumb2, $gridElement);
                    $gridElement = str_replace('{{ name_girl }}', $arrayBigThumbs[$bigThumbsCount]->wbmerNick, $gridElement);
                    $gridElement = str_replace('{{ width }}', 337, $gridElement);
                    $gridElement = str_replace('{{ height }}', 307, $gridElement);
                    $gridContent = $gridContent . $gridElement;
                    $bigThumbsCount++;
                }
                $gridElement = $piece;
                $lineCount++;
                $gridElement = str_replace('{{ special_class }}', '', $gridElement);
                $gridElement = str_replace('{{ url_girl }}', $urlJoin . $thumb->wbmerPermalink, $gridElement);
                $gridElement = str_replace('{{ image_girl }}', $thumb->wbmerThumb2, $gridElement);
                $gridElement = str_replace('{{ name_girl }}', $thumb->wbmerNick, $gridElement);
                $gridElement = str_replace('{{ width }}', 175, $gridElement);
                $gridElement = str_replace('{{ height }}', 150, $gridElement);
                $gridContent = $gridContent . $gridElement;
                continue;
            }
// Lasts six
            if ($lineCount < 12) {
                $gridElement = $piece;
                $lineCount++;
                $gridElement = str_replace('{{ special_class }}', '', $gridElement);
                $gridElement = str_replace('{{ url_girl }}', $urlJoin . $thumb->wbmerPermalink, $gridElement);
                $gridElement = str_replace('{{ image_girl }}', $thumb->wbmerThumb2, $gridElement);
                $gridElement = str_replace('{{ name_girl }}', $thumb->wbmerNick, $gridElement);
                $gridElement = str_replace('{{ width }}', 175, $gridElement);
                $gridElement = str_replace('{{ height }}', 150, $gridElement);
                $gridContent = $gridContent . $gridElement;
                continue;
            }
            $gridElement = $piece;
            $lineCount = 1;
            $gridElement = str_replace('{{ special_class }}', '', $gridElement);
            $gridElement = str_replace('{{ url_girl }}', $urlJoin . $thumb->wbmerPermalink, $gridElement);
            $gridElement = str_replace('{{ image_girl }}', $thumb->wbmerThumb2, $gridElement);
            $gridElement = str_replace('{{ name_girl }}', $thumb->wbmerNick, $gridElement);
            $gridElement = str_replace('{{ width }}', 175, $gridElement);
            $gridElement = str_replace('{{ height }}', 150, $gridElement);
            $gridContent = $gridContent . $gridElement;
        }
    }
}