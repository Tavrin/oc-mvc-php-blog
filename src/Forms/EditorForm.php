<?php


namespace App\Forms;


use Core\controller\Form;
use Core\http\Request;
use Core\http\Session;

class EditorForm extends Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options)
    {
        parent::__construct($request, $entity, $session, $options);


        $this->addCss('w-75');
        $this->addTextInput('title', ['class' => 'form-control js-binder', 'placeholder' => "Titre", 'dataAttributes' => ['type' => 'text', 'target' => 'slug', 'target-attribute' => 'value', 'options' => ['slugify' => true]], 'value' => 'edit' === $options['type']? $entity->getTitle() : null]);
        $this->addTextInput('metaTitle', ['class' => 'form-control', 'placeholder' => "Méta titre",  'value' => 'edit' === $options['type']? $entity->getMetaTitle() : null]);
        $this->addTextInput('metaDescription', ['class' => 'form-control', 'placeholder' => "Méta description",  'value' => 'edit' === $options['type']? $entity->getMetaDescription() : null]);
        $this->addTextInput('slug', ['class' => 'form-control', 'placeholder' => "Slug", 'value' => 'edit' === $options['type']? $entity->getSlug() : null]);
        $this->addSelectInput('category', $options['selection'], ['class' => 'form-control w-75', 'placeholder' => 'choisissez une catégorie', 'label' => 'catégorie :', 'targetField' => 'id', 'selected' => 'edit' === $options['type']? $entity->getCategory()->getId() : null]);
        $this->addDateTimeInput('publishedAt', ['class' => 'form-control', 'placeholder' => "Date de publication", 'value' => 'edit' === $options['type']? $entity->getPublishedAt()->format("Y-m-d\TH:i:s") : null]);
        $this->addFileInput('media', ['class' => 'form-control mt-1 mb-1', 'label' => 'Media', 'entity' => false, 'accept' => 'image/*', 'whitelist' => ['mimes' => 'WHITELIST_IMAGE, WHITELIST_VIDEO', 'type' => 'enum']]);
        $this->addHiddenInput('header', ['sanitize' => false]);
        $this->addHiddenInput('content', ['sanitize' => false]);
    }
}