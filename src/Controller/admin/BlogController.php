<?php


namespace App\Controller\admin;

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
        $form = $this->createForm($post, ['name' => 'newPost']);

        $form->addTextInput('header', ['class' => 'form-control', 'placeholder' => "Chapô"]);
        $form->addTextInput('content', ['class' => 'form-control', 'placeholder' => "Contenu"]);
        $form->setSubmitValue('accepter', ['class' => 'button-bb-wc']);

        $form->handle($request);

        if ($form->isValid) {
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