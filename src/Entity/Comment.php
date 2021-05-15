<?php


namespace App\Entity;


use App\Enums\CommentStatus;
use DateTime;

class Comment
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string|null
     */
    private ?string $content = null;

    /**
     * @var int|null
     */
    private ?int $status = null;

    /**
     * @var bool|null
     */
    private ?bool $hidden = null;

    /**
     * @var string|null
     */
    private ?string $slug = null;

    /**
     * @var string|null
     */
    private ?string $path = null;

    /**
     * @var Post
     */
    private Post $post;

    /**
     * @var User|null
     */
    private ?User $user;

    /**
     * @var DateTime|null
     */
    private ?DateTime $createdAt = null;

    /**
     * @var DateTime|null
     */
    private ?DateTime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->hidden = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content)
    {
        $this->content = $content;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user)
    {
        $this->user = $user;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug)
    {
        $this->slug = $slug;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path)
    {
        $this->path = $path;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden)
    {
        $this->hidden = $hidden;
    }
}