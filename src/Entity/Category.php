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
     * @var string|null
     */
    private ?string $description = null;

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

        if ($this->name) {
            $this->setPath(StringUtils::slugify($this->name));
        }
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
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

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}