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
    private ?string $content;

    /**
     * @var int
     */
    private int $status;

    /**
     * @var Post
     */
    private Post $post;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var DateTime|null
     */
    private ?DateTime $publishedAt;

    /**
     * @var DateTime|null
     */
    private ?DateTime $updatedAt;

    public function __construct()
    {
        $this->publishedAt = new DateTime();
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

    public function setUser(User $user)
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

    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;
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
}