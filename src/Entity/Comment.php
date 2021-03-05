<?php


namespace App\Entity;


use App\Enums\CommentStatus;

class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var CommentStatus
     */
    private $status;

    /**
     * @var Post
     */
    private $post;

    /**
     * @var User
     */
    private $author;

    /**
     * @var \DateTime
     */
    private $publishedAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    public function getPublishedAt(): \DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}