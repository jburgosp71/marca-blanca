<?php

namespace WhiteBrand;

class GirlsInfo
{
    private $jsonFile;

    private function __construct(string $file)
    {
        $this->jsonFile = $file;
    }

    public static function take(string $file): self
    {
        return new static($file);
    }

    public function getInfo()
    {
        return json_decode(JsonFile::openJson($this->jsonFile)->getContent());
    }

}