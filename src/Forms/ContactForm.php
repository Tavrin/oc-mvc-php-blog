<?php


namespace App\Forms;


use Core\controller\Form;
use Core\http\Request;
use Core\http\Session;

class ContactForm extends Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options = [])
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addTextInput('fullName', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Prénom / Nom'])
            ->addEmailInput('email', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email'])
            ->addTextareaInput('content',['class' => 'form-control', 'placeholder' => "Ecrire un message", 'value' => '', 'label' => 'Message', 'rows' => 5, 'cols' => 35])
            ->setSubmitValue('Envoyer', ['class' => 'button-bb-wc-2 as-c br-5 mt-1'])
            ->addCheckbox('consent', ['class' => 'text-muted', 'label' => 'Je consent à partager mes données personnelles ci-jointes avec tavrin.io', 'inputBeforeLabel' => true, 'entity' => false,])
            ->addCss('d-f fd-c');
    }
}