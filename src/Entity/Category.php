<?php


namespace App\Entity;


use Core\utils\StringUtils;
use DateTime;

class Category
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $path = null;

    /**
     * @var string
     */
    private string $slug;

    /**
     * @var string
     */
    private string $uuid;

    /**
     * @var Media|null
     */
    private ?Media $media = null;

    /**
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @var string|null
     */
    private ?string $metaTitle;
    /**
     * @var string|null
     */
    private ?string $metaDescription;

    /**
     * @var bool
     */
    private ?bool $status = true;

    /**
     * @var DateTime
     */
    private DateTime $createdAt;

    /**
     * @var DateTime|null
     */
    private ?DateTime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->status = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status)
    {
        $this->status = $status;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function setMedia(?Media $media)
    {
        $this->media = $media;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $publishedAt)
    {
        $this->createdAt = $publishedAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        if (!$this->updatedAt) {
            return null;
        }

        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}