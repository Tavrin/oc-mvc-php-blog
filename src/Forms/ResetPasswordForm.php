<?php


namespace App\Forms;


use Core\http\Request;
use Core\http\Session;

class ResetPasswordForm extends \Core\controller\Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options)
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Nouveau mot de passe', 'fieldName' => 'password', 'hash' => true]);
        $this->addPasswordInput('passwordConfirm', ['entity' => false, 'required' => true, 'class' => 'form-control', 'label' => 'Confirmation','placeholder' => 'Confirmation de nouveau mot de passe']);
        $this->setSubmitValue('Changer le mot de passe', ['class' => 'button-bb-wc']);

    }
}