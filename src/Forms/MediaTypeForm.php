<?php


namespace App\Forms;


use Core\http\Request;
use Core\http\Session;

class MediaTypeForm extends \Core\controller\Form
{
    public function __construct(Request $request, object $entity, Session $session, array $options = [])
    {
        parent::__construct($request, $entity, $session, $options);

        $this->addTextInput('name', ['class' => 'form-control js-binder', 'placeholder' => "Nom du média", 'label' => 'Nom','dataAttributes' => ['type' => 'text', 'target' => 'slug', 'target-attribute' => 'value', 'options' => ['slugify' => true]], 'value' => 'edit' === $options['type']? $entity->getName() : null])
            ->addCss('w-75')
            ->addDateTimeInput('createdAt', ['class' => 'form-control', 'placeholder' => "Date de création", 'value' => 'edit' === $options['type']? $entity->getCreatedAt()->format("Y-m-d\TH:i:s") : null])
            ->setSubmitValue('accepter', ['class' => 'button-bb-wc mt-1 ta-c'])
        ;

    }
}