<?php


namespace App\Forms;


use Core\controller\Form;
use Core\http\Request;
use Core\http\Session;

class LoginForm extends Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options = [])
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addEmailInput('email', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email']);
        $this->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe']);
        $this->setSubmitValue('accepter', ['class' => 'button-bb-wc']);
    }
}