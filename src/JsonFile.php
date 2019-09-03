<?php

class JsonFile
{
    private $jsonFile;

    private function __construct(string $file)
    {
    $this->jsonFile = $file;
    }

    public static function openJson(string $file): self
    {
    return new static($file);
    }

    /**
    * @return string
    */
    public function getContent(): string
    {
    return file_get_contents($this->jsonFile, TRUE);
    }
}