<?php


namespace App\Email;

use Core\email\Email;
use App\Entity\User;

class VerificationEmail extends Email
{
    public function __construct(User $user)
    {
        parent::__construct();

        $this->addReceiver($user->getEmail());
        $this->subject('Email de vérification');
        $this->setRender('email/email-verification.html.twig', ['user' => $user]);
    }
}