<?php 

namespace App\Entity;





class MediaType
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
    * @var bool
    */
    private bool $status;

    /**
    * @var string
    */
    private string $uuid;

    /**
     * @var string
     */
    private string $slug;

    /**
    * @var \Datetime
    */
    private \Datetime $createdAt;

    /**
    * @var \Datetime|null
    */
    private ?\Datetime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

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

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
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

    public function getCreatedAt(): \Datetime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\Datetime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\Datetime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\Datetime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

}