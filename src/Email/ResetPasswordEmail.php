<?php


namespace App\Email;

use Core\email\Email;
use App\Entity\User;

class ResetPasswordEmail extends Email
{
    public function __construct(User $user)
    {
        parent::__construct();

        $this->addReceiver($user->getEmail());
        $this->subject('Demande de réinitialisation du mot de passe');
        $this->setRender('email/password-reset.html.twig', ['user' => $user]);
    }
}