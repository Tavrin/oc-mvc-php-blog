<?php 

namespace App\Entity;








use Datetime;

class Media
{
     /**
     * @var int
     */
     private int $id;

    /**
    * @var string
    */
    private string $name;

    /**
    * @var string|null
    */
    private ?string $alt;

    /**
    * @var MediaType
    */
    private MediaType $type;

    /**
    * @var string
    */
    private string $path;

    /**
     * @var string
     */
    private string $slug;

    /**
    * @var string
    */
    private string $uuid;

    /**
    * @var bool
    */
    private bool $status;

    /**
    * @var Datetime
    */
    private Datetime $createdAt;

    /**
    * @var Datetime|null
    */
    private ?Datetime $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt)
    {
        $this->alt = $alt;
    }

    public function getType(): MediaType
    {
        return $this->type;
    }

    public function setType(MediaType $type)
    {
        $this->type = $type;
    }

    public function getPath(): string
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    public function getCreatedAt(): datetime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(datetime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?datetime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?datetime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

}