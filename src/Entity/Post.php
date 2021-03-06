<?php


namespace App\Entity;

class Post
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $header;

    /**
     * @var string
     */
    private $content;

    /**
     * @var array
     */
    private $readmore;

    /**
     * @var string
     */
    private $metaTitle;

    /**
     * @var string
     */
    private $metaDescription;

    /**
     * @var string
     */
    private $listingText;

    /**
     * @var string
     */
    private $slug;

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

    /**
     * @var bool
     */
    private $status;

    public function __construct()
    {
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

    public function setReadmore(array $readMore)
    {
        $this->readmore = $readMore;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(string $metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(string $metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    public function getListingText(): ?string
    {
        return $this->listingText;
    }

    public function setListingText(string $listingText)
    {
        $this->listingText = $listingText;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
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