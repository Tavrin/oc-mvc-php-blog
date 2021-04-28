<?php


namespace App\Forms;


use Core\controller\Form;
use Core\http\Request;
use Core\http\Session;

class UserEditorForm extends Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options)
    {
        parent::__construct($request, $entity, $session, $options);
        $media = '//:0';
        if ($entity->getMedia()) {
            $media = $entity->getMedia()->getPath();
        }
        $statusSelection = [['id' => 'true', 'placeholder' => 'activé'],['id' => 'false', 'placeholder' => 'désactivé']];

        true === $entity->getStatus() ? $selected = 'true' : $selected = 'false';

        $this->addCss('w-75')
            ->addTextInput('username', ['class' => 'form-control js-binder', 'pattern' =>'[A-Za-z0-9]+', 'placeholder' => "Nom d'utilisateur", 'label' => 'Nom d\'utilisateur', 'dataAttributes' => ['type' => 'text', 'target' => 'slug', 'target-attribute' => 'value', 'options' => ['slugify' => true]], 'value' => 'edit' === $options['type']? $entity->getUsername() : null])
            ->addTextInput('email', ['class' => 'form-control', 'label' => 'Email', 'placeholder' => "adresse email de l'utilisateur",  'value' => 'edit' === $options['type']? $entity->getEmail() : null])
            ->addPasswordInput('password', ['required' => false, 'class' => 'form-control', 'placeholder' => 'Mot de passe', 'fieldName' => 'password', 'hash' => true, 'modifyIfEmpty' => false])
            ->addPasswordInput('passwordConfirm', ['entity' => false, 'required' => false, 'class' => 'form-control', 'label' => 'Confirmation','placeholder' => 'Confirmation du mot de passe'])
            ->addTextInput('firstName', ['class' => 'form-control', 'placeholder' => "Prénom", 'required' => false, 'value' => 'edit' === $options['type']? $entity->getFirstName() : null])
            ->addTextInput('lastName', ['class' => 'form-control', 'placeholder' => "Nom de famille", 'required' => false,  'value' => 'edit' === $options['type']? $entity->getLastName() : null])
            ->addHiddenInput('slug', ['class' => 'form-control', 'placeholder' => "Slug", 'value' => 'edit' === $options['type']? $entity->getSlug() : null])
            ->addDateTimeInput('createdAt', ['class' => 'form-control', 'placeholder' => "Date de création", 'value' => 'edit' === $options['type']? $entity->getCreatedAt()->format("Y-m-d\TH:i:s") : null])
            ->addSelectInput('status', $statusSelection, ['class' => 'form-control w-75', 'placeholder' => 'Statut de l\'utilisateur', 'label' => 'Statut :', 'targetField' => 'status', 'selected' => $selected])
            ->addHiddenInput('mediaHiddenInput', ['entity' => false, 'class' => 'js-binder', 'value' => 'edit' === $options['type']? $media : '#', 'dataAttributes' => ['type' => 'image', 'from' => 'modal', 'target' => 'previewImage']])
            ->addButton('mediaLibrary', ['class' => 'js-modal button-bb-wc m-1', 'value' => 'Galerie média', 'type' => 'button', 'dataAttributes' => ['target-modal' => 'mediaModal']])
            ->addDiv('mediaShow', ['class' => 'hrem-6 js-filler', 'dataAttributes' => ['type' => 'image', 'id' => 'previewImage', 'class' => 'mh-100 mw-100', 'src' => 'edit' === $options['type']? $media : ''], 'wrapperClass' => 'mt-1', 'label' => 'Prévisualisation de l\'image'])
            ->addTextareaInput('presentation',['class' => 'form-control', 'placeholder' => "Présentation", 'required' => false, 'value' => 'edit' === $options['type']? $entity->getPresentation() : '', 'label' => 'Description', 'rows' => 5])
            ->setSubmitValue('accepter', ['class' => 'button-bb-wc mt-1 ta-c']);
    }
}