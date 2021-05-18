<?php


namespace Core\file;


class PublicFile extends File
{
    public function __construct($filename)
    {
        $filename = ROOT_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . trim($filename, '/\\');
        parent::__construct($filename);
    }
}