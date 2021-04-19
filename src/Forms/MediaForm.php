<?php


namespace App\Forms;


use Core\controller\Form;
use Core\http\Request;
use Core\http\Session;

class MediaForm extends Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options = [])
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addTextInput('name', ['class' => 'form-control js-binder', 'placeholder' => "Nom du média", 'label' => 'Nom','dataAttributes' => ['type' => 'text', 'target' => 'slug', 'target-attribute' => 'value', 'options' => ['slugify' => true]], 'value' => 'edit' === $options['type']? $entity->getName() : null])
            ->addCss('w-75')
            ->addTextInput('alt', ['class' => 'form-control', 'placeholder' => "Alt du média", 'label' => 'Alt', 'value' => 'edit' === $options['type']? $entity->getAlt() : null])
            ->addTextInput('slug', ['class' => 'form-control', 'placeholder' => "Nom machine", 'label' => 'Slug', 'value' => 'edit' === $options['type']? $entity->getSlug() : null])
            ->addDateTimeInput('createdAt', ['class' => 'form-control', 'placeholder' => "Date de création", 'value' => 'edit' === $options['type']? $entity->getCreatedAt()->format("Y-m-d\TH:i:s") : null])
            ->addDateTimeInput('updatedAt', ['class' => 'form-control', 'placeholder' => "Date de modification", 'value' => 'edit' === $options['type']? $entity->getUpdatedAt()->format("Y-m-d\TH:i:s") : null])
            ->addDiv('mediaShow', ['class' => 'hrem-6 js-filler', 'dataAttributes' => ['type' => 'image', 'id' => 'previewImage', 'class' => 'mh-100 mw-100', 'src' => 'edit' === $options['type']? $entity->getPath() : ''], 'wrapperClass' => 'mt-1', 'label' => 'Prévisualisation de l\'image'])
            ->addFileInput('mediaFile',['class' => 'form-control mt-1 mb-1 js-binder', 'dataAttributes' => ['type' => 'image', 'from' => 'file', 'target' => 'previewImage'], 'label' => 'Media', 'entity' => false, 'accept' => 'image/*', 'whitelist' => ['mimes' => 'WHITELIST_IMAGE', 'type' => 'enum']])
            ->setSubmitValue('accepter', ['class' => 'button-bb-wc mt-1 ta-c'])
        ;

    }
}