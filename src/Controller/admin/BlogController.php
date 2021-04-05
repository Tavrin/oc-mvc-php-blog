<?php


namespace App\Controller\admin;

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
        $post = new Post();
        $categoryRepo = new CategoryRepository($this->getManager());
        $categories = $categoryRepo->findAll();
        $selection = [];
        foreach ($categories as $key => $category) {
            $selection[$key]['id'] = $category->getId();
            $selection[$key]['placeholder'] = $category->getName();
        }

        $form = $this->createForm($post, ['name' => 'newPost','submit' => false]);
        $form->addCss('w-75');
        $form->addTextInput('title', ['class' => 'form-control', 'placeholder' => "Titre"]);
        $form->addSelectInput('category', $selection, ['class' => 'form-control w-75', 'placeholder' => 'choisissez une catégorie', 'label' => 'catégorie :', 'targetField' => 'id']);
        $form->addDateTimeInput('publishedAt', ['class' => 'form-control', 'placeholder' => "Date de publication"]);
        $form->addHiddenInput('header', ['entity' => false]);
        $form->addHiddenInput('content', ['entity' => false]);

        $form->handle($request);

        if ($form->isValid) {
            dd($post);
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