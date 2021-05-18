<?php


namespace App\Forms;


use Core\http\Request;
use Core\http\Session;

class CategoryEditorForm extends \Core\controller\Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options)
    {
        parent::__construct($request, $entity, $session, $options);
        $media = null;
        if ($entity->getMedia()) {
            $media = $entity->getMedia()->getPath();
        }

        $this->addCss('w-75')
            ->addTextInput('name', ['class' => 'form-control js-binder', 'placeholder' => "Nom de la catégorie", 'label' => 'Nom', 'dataAttributes' => ['type' => 'text', 'target' => 'slug', 'target-attribute' => 'value', 'options' => ['slugify' => true]], 'value' => 'edit' === $options['type']? $entity->getName() : null])
            ->addTextInput('metaTitle', ['class' => 'form-control', 'placeholder' => "Méta titre",  'value' => 'edit' === $options['type']? $entity->getMetaTitle() : null])
            ->addTextInput('metaDescription', ['class' => 'form-control', 'placeholder' => "Méta description",  'value' => 'edit' === $options['type']? $entity->getMetaDescription() : null])
            ->addTextInput('slug', ['class' => 'form-control', 'placeholder' => "Slug", 'value' => 'edit' === $options['type']? $entity->getSlug() : null])
            ->addDateTimeInput('createdAt', ['class' => 'form-control', 'placeholder' => "Date de publication", 'value' => 'edit' === $options['type']? $entity->getCreatedAt()->format("Y-m-d\TH:i:s") : null])
            ->addHiddenInput('mediaHiddenInput', ['entity' => false, 'class' => 'js-binder', 'value' => 'edit' === $options['type']? $media : '', 'dataAttributes' => ['type' => 'image', 'from' => 'modal', 'target' => 'previewImage']])
            ->addButton('mediaLibrary', ['class' => 'js-modal button-bb-wc m-1', 'value' => 'Galerie média', 'type' => 'button', 'dataAttributes' => ['target-modal' => 'mediaModal']])
            ->addDiv('mediaShow', ['class' => 'hrem-6 js-filler', 'dataAttributes' => ['type' => 'image', 'id' => 'previewImage', 'class' => 'mh-100 mw-100', 'src' => 'edit' === $options['type']? $media : ''], 'wrapperClass' => 'mt-1', 'label' => 'Prévisualisation de l\'image'])
            ->addTextareaInput('description',['class' => 'form-control', 'required' => false,'placeholder' => "Description de la catégorie", 'value' => 'edit' === $options['type']? $entity->getDescription() : '', 'label' => 'Description', 'rows' => 5])
            ->setSubmitValue('accepter', ['class' => 'button-bb-wc mt-1 ta-c']);
    }
}