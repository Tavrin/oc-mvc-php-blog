<?php


namespace App\Forms;


use Core\http\Request;
use Core\http\Session;

class CategoryEditorForm extends \Core\controller\Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options)
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addCss('w-75')
            ->addTextInput('name', ['class' => 'form-control js-binder', 'placeholder' => "Titre", 'dataAttributes' => ['type' => 'text', 'target' => 'slug', 'target-attribute' => 'value', 'options' => ['slugify' => true]], 'value' => 'edit' === $options['type']? $entity->getTitle() : null])
            ->addTextInput('metaTitle', ['class' => 'form-control', 'placeholder' => "Méta titre",  'value' => 'edit' === $options['type']? $entity->getMetaTitle() : null])
            ->addTextInput('metaDescription', ['class' => 'form-control', 'placeholder' => "Méta description",  'value' => 'edit' === $options['type']? $entity->getMetaDescription() : null])
            ->addTextInput('slug', ['class' => 'form-control', 'placeholder' => "Slug", 'value' => 'edit' === $options['type']? $entity->getSlug() : null])
            ->addDateTimeInput('publishedAt', ['class' => 'form-control', 'placeholder' => "Date de publication", 'value' => 'edit' === $options['type']? $entity->getPublishedAt()->format("Y-m-d\TH:i:s") : null])
            ->addFileInput('media', ['class' => 'form-control mt-1 mb-1', 'label' => 'Media', 'entity' => false, 'accept' => 'image/*', 'whitelist' => ['mimes' => 'WHITELIST_IMAGE, WHITELIST_VIDEO', 'type' => 'enum']])
            ->addTextareaInput('description',['class' => 'form-control', 'placeholder' => "Description de la catégorie", 'label' => 'Description', 'rows' => 5]);
    }
}