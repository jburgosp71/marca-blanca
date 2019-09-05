<?php

require_once __DIR__ . '/../vendor/autoload.php';

use WhiteBrand\JsonFile;
use WhiteBrand\ParseUrl;
use WhiteBrand\Template;

$config = json_decode(JsonFile::openJson("../configs/config.json")->getContent());
$template_config = json_decode(JsonFile::openJson("../configs/template_config.json")->getContent());
$affiliates = json_decode(JsonFile::openJson($config->affiliate_file)->getContent());

$affiliate=ParseUrl::getHost()->getNameHost();

if(!empty($affiliates->$affiliate)){
    $templateInfo = new Template($template_config, $affiliates->$affiliate);
    $html = $templateInfo->getHeader() . $templateInfo->getBody() . $templateInfo->getFooter();
    echo $html;
} else {
    header("Location: " . $config->cumlauder_url);
    die();
}


