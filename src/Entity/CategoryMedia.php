<?php 

namespace App\Entity;
use App\Entity\Category;

use App\Entity\Media;




class CategoryMedia
{
     /**
     * @var int
     */
     private int $id;

    /**
    * @var Category
    */
    private Category $category;

    /**
    * @var Media
    */
    private Media $media;

    /**
    * @var \Datetime
    */
    private \Datetime $createdAt;

    /**
    * @var \Datetime|null
    */
    private ?\Datetime $updatedAt;

    /**
    * @var bool
    */
    private bool $status;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function setMedia(Media $media)
    {
        $this->media = $media;
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

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

}