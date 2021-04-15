<?php


namespace Core\file;


class File extends \SplFileInfo
{
    protected string $filePath;
    public function __construct($filename)
    {
        $this->filePath = $filename;
        parent::__construct($filename);
    }

    public function getName()
    {}

    public function put(string $path, string $name = null)
    {
        $targetPath = $this->getFilePath($path, $name);
        rename($this->getPathname(), $targetPath);

        @chmod($targetPath, 0666 & ~umask());
        $this->filePath = $targetPath;

        return $targetPath;
    }

    protected function getFilePath(string $path, string $name = null): string
    {
        $path = ROOT_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . trim($path, '/\\');
        if (!is_dir($path) && false === @mkdir($path, 0777, true)) {
            throw new FileException("Cant create the path {$path}");
        } elseif (!is_writeable($path)) {
            throw new FileException('Path is not writable');
        }

        if (null === $name) {
            $name = $this->getBasename();
        }

        $name = trim($name, '/\\');

        return $path . DIRECTORY_SEPARATOR . $name;
    }

    public function getMime()
    {
        return mime_content_type($this->filePath);
    }
}