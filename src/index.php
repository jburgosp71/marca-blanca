<?php
require "JsonFile.php";
require "Template.php";

$config = json_decode(JsonFile::openJson("../configs/config.json")->getContent());
$template_config = json_decode(JsonFile::openJson("../configs/template_config.json")->getContent());
$affiliates = json_decode(JsonFile::openJson($config->affiliate_file)->getContent());

$afiliado="cerdas";
if(!empty($affiliates->$afiliado)){
    var_dump($affiliates->$afiliado);
} else {
    echo "No hay info";
}

