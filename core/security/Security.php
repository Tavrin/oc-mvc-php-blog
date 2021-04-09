<?php


namespace Core\security;

use App\Entity\User;
use App\Repository\UserRepository;
use Core\http\Session;
use Ramsey\Uuid\Uuid;


class Security
{
    protected Session $session;
    private ?User $user = null;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->session = new Session();
        $this->userRepository = new UserRepository();
        $this->session->start();
        $this->setUser();
    }

    public function setCSRFToken()
    {
        $token = Uuid::uuid4();

        $oldToken = $this->session->get('csrf-new');
        $this->session->set('csrf-old', $oldToken);
        $this->session->set('csrf-new', $token);
    }

    private function setUser(){
        if ($this->session->has('user')) {
            $this->user = $this->session->get('user');
        }
    }

    public function getUser(): ?User
    {
        if (!isset($this->user) || !$this->user instanceof User) {
            return null;
        }

        return $this->user;
    }

    public function hasRole(string $role): bool
    {
        if (!isset($this->user) || !$this->user instanceof User) {
            return false;
        }

        if (in_array($role, $this->user->getRoles())) {
            return true;
        }

        return false;
    }

    public function verifyLoggedUser()
    {
        if (!isset($this->user)) {
            return;
        }

        if (null === $entityUser = $this->userRepository->findOneBy('email', $this->user->getEmail())) {
            $this->session->remove('user');
            header("Location:/");
            exit();
        }

        if ($this->user != $entityUser) {
            $this->session->remove('user');
            header("Location:/");
            exit();
        }
    }
}