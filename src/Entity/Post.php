<?php


namespace App\Entity;

class Post
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string|null
     */
    private ?string $title;

    /**
     * @var string|null
     */
    private ?string $header;

    /**
     * @var string|null
     */
    private ?string $content;

    /**
     * @var array|null
     */
    private ?array $readmore = [];

    /**
     * @var string|null
     */
    private ?string $metaTitle;

    /**
     * @var string|null
     */
    private ?string $metaDescription;

    /**
     * @var string|null
     */
    private ?string $slug;

    /**
     * @var string|null
     */
    private ?string $path;

    /**
     * @var User|null
     */
    private ?User $author = null;

    /**
     * @var Category|null
     */
    private ?Category $category;

    /**
     * @var \DateTime
     */
    private \DateTime $publishedAt;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $updatedAt = null;

    /**
     * @var bool
     */
    private ?bool $status;

    public function __construct()
    {
        $this->status = false;
        $this->publishedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(string $header)
    {
        $this->header = $header;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getReadmore(): ?array
    {
        return $this->readmore;
    }

    public function setReadmore(?array $readMore)
    {
        $this->readmore = $readMore;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function getPublishedAt(): \DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }
}