<?php


namespace App\Email;

use App\Entity\Message;
use Core\email\Email;

class ContactEmail extends Email
{
    public function __construct(Message $message, string $receiver)
    {
        parent::__construct();

        $this->addReceiver($receiver);
        $this->subject('Contact Tavrin.io');
        $this->setRender('email/email-contact.html.twig', ['content' => $message]);
    }
}