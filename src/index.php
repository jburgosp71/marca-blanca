<?php
require "JsonFile.php";
require "Template.php";
require "ParseUrl.php";

$config = json_decode(JsonFile::openJson("../configs/config.json")->getContent());
$template_config = json_decode(JsonFile::openJson("../configs/template_config.json")->getContent());
$affiliates = json_decode(JsonFile::openJson($config->affiliate_file)->getContent());

$afiliado=ParseUrl::getHost()->getNameHost();

if(!empty($affiliates->$afiliado)){
    $templateInfo = new Template($template_config, $affiliates->$afiliado);
    $html = $templateInfo->getHeader() . $templateInfo->getBody() . $templateInfo->getFooter();
    echo $html;
} else {
    header("Location: " . $config->url_cumlauder);
    die();
}


