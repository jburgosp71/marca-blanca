<?php

/*
require "JsonFile.php";
require "Template.php";
require "ParseUrl.php";
*/

require_once  __DIR__.'/vendor/autoload.php';

use WhiteBrand\JsonFile;
use WhiteBrand\ParseUrl;
use WhiteBrand\GirlsInfo;
use WhiteBrand\Template;

$config = json_decode(JsonFile::openJson("../configs/config.json")->getContent());
$template_config = json_decode(JsonFile::openJson("../configs/template_config.json")->getContent());
$affiliates = json_decode(JsonFile::openJson($config->affiliate_file)->getContent());

$afiliado=ParseUrl::getHost()->getNameHost();

if(!empty($affiliates->$afiliado)){
    $girlsInfo = GirlsInfo::take($config->girls_url);
    $templateInfo = new Template($template_config, $affiliates->$afiliado, $girlsInfo);
    $html = $templateInfo->getHeader() . $templateInfo->getBody() . $templateInfo->getFooter();
    echo $html;
} else {
    header("Location: " . $config->cumlauder_url);
    die();
}


