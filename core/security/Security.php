<?php


namespace Core\security;

use Core\http\Session;
use Ramsey\Uuid\Uuid;


class Security
{
    protected Session $session;
    public function __construct()
    {
        $this->session = new Session();
        $this->session->start();
    }

    public function setCSRFToken()
    {
        $token = Uuid::uuid4();

        $oldToken = $this->session->get('csrf-new');
        $this->session->set('csrf-old', $oldToken);
        $this->session->set('csrf-new', $token);
    }
}