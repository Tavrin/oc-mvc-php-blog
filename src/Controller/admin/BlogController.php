<?php


namespace App\Controller\admin;

use App\Manager\BlogManager;
use App\Repository\CategoryRepository;
use Core\controller\Controller;
use Core\http\Request;
use App\Entity\Post;
use Core\http\Response;

class BlogController extends Controller
{
    public function indexAction()
    {
        $this->getManager()->findAll('post');
    }

    public function showAction()
    {

    }

    public function newAction(Request $request): Response
    {
        $em = $this->getManager();
        $post = new Post();
        $blogManager = new BlogManager($em);
        $selection = $blogManager->getSelection('category', ['placeholder' => 'name']);

        $form = $this->createForm($post, ['name' => 'newPost','submit' => false]);
        $form->addCss('w-75');
        $form->addTextInput('title', ['class' => 'form-control js-binder', 'placeholder' => "Titre", 'dataAttributes' => ['type' => 'text', 'target' => 'slug', 'target-attribute' => 'value', 'options' => ['slugify' => true]]]);
        $form->addTextInput('metaTitle', ['class' => 'form-control', 'placeholder' => "Méta titre"]);
        $form->addTextInput('metaDescription', ['class' => 'form-control', 'placeholder' => "Méta description"]);
        $form->addTextInput('slug', ['class' => 'form-control', 'placeholder' => "Slug"]);
        $form->addSelectInput('category', $selection, ['class' => 'form-control w-75', 'placeholder' => 'choisissez une catégorie', 'label' => 'catégorie :', 'targetField' => 'id']);
        $form->addDateTimeInput('publishedAt', ['class' => 'form-control', 'placeholder' => "Date de publication"]);
        $form->addHiddenInput('header', ['sanitize' => false]);
        $form->addHiddenInput('content', ['sanitize' => false]);

        $form->handle($request);

        if ($form->isValid) {
            if ($blogManager->savePost($post, $this->getUser())) {
                $this->redirect('/admin', ['type' => 'success', 'message' => 'Article publié avec succès']);
            }

            $this->redirect('/admin/posts/new', ['type' => 'danger', 'message' => 'Une erreur s\'est produite durant l\'enregistrement en base de données']);
        }

        return $this->render('blog/new.html.twig', [
            'form' => $form->renderForm()
        ]);
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }
}