<?php


namespace Core\file;


class File extends \SplFileInfo
{
    protected string $filePath;
    protected ?string $relativePath = null;
    public function __construct($filename)
    {
        $this->filePath = $filename;
        parent::__construct($filename);
    }

    public function getName(): string
    {
        return $this->getPathname();
    }

    public function put(string $path, string $name = null): string
    {
        $targetPath = $this->getTargetPath($path, $name);
        rename($this->getPathname(), $targetPath);

        @chmod($targetPath, 0666 & ~umask());
        $this->filePath = $targetPath;
        $this->relativePath = str_replace( ROOT_DIR . DIRECTORY_SEPARATOR . 'public','', $this->filePath,);
        return $targetPath;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getRelativePath(): string
    {
        if (isset($this->relativePath)) {
            return $this->relativePath;
        }

        return str_replace( ROOT_DIR . DIRECTORY_SEPARATOR . 'public','', $this->filePath,);
    }

    protected function getTargetPath(string $path, string $name = null): string
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

    public function delete()
    {
        unlink($this->getFilePath());
    }
}