<?php


namespace App\Entity;


class User
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string|null
     */
    private ?string $fullName = null;

    /**
     * @var string|null
     */
    private ?string $presentation = null;

    /**
     * @var ?bool
     */
    private ?bool $status = false;

    /**
     * @var string
     */
    private string $uuid;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string|null
     */
    private ?string $token = null;

    /**
     * @var string|null
     */
    private ?string $slug = null;

    /**
     * @var string|null
     */
    private ?string $path;

    /**
     * @var array|null
     */
    private ?array $roles = null;

    /**
     * @var Media|null
     */
    private ?Media $media = null;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $createdAt = null;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $updatedAt = null;

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $lastConnexion = null;

    public function __construct()
    {
        if (!$this->getCreatedAt()) {
            $this->createdAt = new \DateTime();
        }

        $this->roles[] = 'ROLE_USER';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName)
    {
        $this->fullName = $fullName;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation)
    {
        $this->presentation = $presentation;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status)
    {
        $this->status = $status;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token)
    {
        $this->token = $token;
    }

    public function getRoles(): ?array
    {
        $roles = $this->roles;

        if (!in_array('ROLE_USER',$roles, true)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
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

    public function setMedia(?Media $media)
    {
        $this->media = $media;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getLastConnexion(): ?\DateTime
    {
        return $this->lastConnexion;
    }

    public function setLastConnexion(?\DateTime $lastConnexion)
    {
        $this->lastConnexion = $lastConnexion;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }
}