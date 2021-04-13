<?php


namespace App\Forms;


use Core\http\Request;
use Core\http\Session;

class ResetPasswordForm extends \Core\controller\Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options)
    {
        parent::__construct($request, $entity, $session, $options);

        $form->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Nouveau mot de passe', 'fieldName' => 'password', 'hash' => true]);
        $form->addPasswordInput('passwordConfirm', ['entity' => false, 'required' => true, 'class' => 'form-control', 'label' => 'Confirmation','placeholder' => 'Confirmation de nouveau mot de passe']);
        $form->setSubmitValue('Changer le mot de passe', ['class' => 'button-bb-wc']);

    }
}