<?php


namespace Core\file;


class FormFile extends File
{
    private ?string $uploadName = null;
    private ?string $uploadMime = null;
    private array $formData = [];

    public function __construct(string $path, array $formData, string $name = null, string $mime = null)
    {
        parent::__construct($path);
        $this->uploadName = $name;
        $this->uploadMime = $mime;
        $this->formData = $formData;

    }

    public function getFormData(): array
    {
        return $this->formData;
    }

    public function getUploadName(): ?string
    {
        return $this->uploadName;
    }

    public function put(string $path, string $name = null): string
    {
        return parent::put($path, $name);
    }

    public function getUploadMime(): ?string
    {
        return $this->uploadMime;
    }
}