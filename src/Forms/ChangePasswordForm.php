<?php


namespace App\Forms;


use Core\http\Request;
use Core\http\Session;

class ChangePasswordForm extends \Core\controller\Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options)
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addPasswordInput('oldPassword', ['entity' => false, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe actuel']);
        $this->addPasswordInput('newPassword', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Nouveau mot de passe', 'fieldName' => 'password', 'hash' => true]);
        $this->addPasswordInput('newPasswordConfirm', ['entity' => false, 'required' => true, 'class' => 'form-control', 'label' => 'Confirmation','placeholder' => 'Confirmation de nouveau mot de passe']);
        $this->setSubmitValue('accepter', ['class' => 'button-bb-wc']);

    }
}