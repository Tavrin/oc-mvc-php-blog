<?php


namespace App\Forms;


use Core\controller\Form;
use Core\http\Request;
use Core\http\Session;

class EditorForm extends Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options = [])
    {
        parent::__construct($request, $entity, $session, $options);

        $category = null;
        $media = null;
        if ($entity->getCategory()) {
            $category = $entity->getCategory()->getId();
        }

        $Selection = [['id' => 'true', 'placeholder' => 'activé'],['id' => 'false', 'placeholder' => 'désactivé']];

        true === $entity->getStatus() ? $selected = 'true' : $selected = 'false';
        true === $entity->getFeatured() ? $selectedFeatured = 'true' : $selectedFeatured = 'false';

        if ($entity->getMedia()) {
            $media = $entity->getMedia()->getPath();
        }

        $entityUpdated = null;
        if ('edit' === $options['type']) {
            $entityUpdated = $entity->getUpdatedAt();
            isset($entityUpdated) ? $entityUpdated = $entityUpdated->format("Y-m-d\TH:i:s") : $entityUpdated = null;
        }

        $this->addCss('w-75')
            ->addTextInput('title', ['class' => 'form-control js-binder', 'placeholder' => "Titre", 'dataAttributes' => ['type' => 'text', 'target' => 'slug', 'target-attribute' => 'value', 'options' => ['slugify' => true]], 'value' => 'edit' === $options['type']? $entity->getTitle() : null])
            ->addTextInput('metaTitle', ['class' => 'form-control', 'placeholder' => "Méta titre",  'value' => 'edit' === $options['type']? $entity->getMetaTitle() : null])
            ->addTextInput('metaDescription', ['class' => 'form-control', 'placeholder' => "Méta description",  'value' => 'edit' === $options['type']? $entity->getMetaDescription() : null])
            ->addTextInput('slug', ['class' => 'form-control', 'placeholder' => "Slug", 'value' => 'edit' === $options['type']? $entity->getSlug() : null])
            ->addSelectInput('category', $options['selection'], ['class' => 'form-control w-75', 'placeholder' => 'choisissez une catégorie', 'label' => 'catégorie :', 'targetField' => 'id', 'selected' => $category])
            ->addDateTimeInput('createdAt', ['class' => 'form-control', 'placeholder' => "Date de publication", 'value' => 'edit' === $options['type']? $entity->getCreatedAt()->format("Y-m-d\TH:i:s") : null])
            ->addDateTimeInput('updatedAt', ['class' => 'form-control', 'placeholder' => "Date de modification", 'value' => 'edit' === $options['type']? $entityUpdated : null])
            ->addSelectInput('status', $Selection, ['class' => 'form-control w-75', 'placeholder' => 'Publié', 'label' => 'Statut :', 'targetField' => 'status', 'selected' => $selected])
            ->addSelectInput('featured', $Selection, ['class' => 'form-control w-75', 'placeholder' => 'En vedette', 'label' => 'En vedette :', 'targetField' => 'featured', 'selected' => $selectedFeatured])
            ->addHiddenInput('mediaHiddenInput', ['entity' => false, 'class' => 'js-binder', 'required' => false,'dataAttributes' => ['type' => 'image', 'from' => 'modal', 'target' => 'previewImage']])
            ->addButton('mediaLibrary', ['class' => 'js-modal button-bb-wc m-1', 'value' => 'Galerie média', 'type' => 'button', 'dataAttributes' => ['target-modal' => 'mediaModal']])
            ->addDiv('mediaShow', ['class' => 'hrem-15 js-filler pt-1', 'dataAttributes' => ['type' => 'image', 'id' => 'previewImage', 'class' => 'mh-80 d-b mw-100', 'src' => 'edit' === $options['type']? $media : ''], 'wrapperClass' => 'mt-1', 'label' => 'Prévisualisation du média principal'])
            ->addHiddenInput('header', ['sanitize' => false])
            ->addHiddenInput('content', ['sanitize' => false]);
    }
}