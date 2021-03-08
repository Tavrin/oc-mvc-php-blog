<?php


namespace App\Controller\admin;

use Core\controller\Controller;
use Core\http\Request;
use App\Entity\Post;

class BlogController extends Controller
{
    public function indexAction()
    {
        $this->getManager()->findAll('post');
    }

    public function showAction()
    {

    }

    public function newAction(Request $request)
    {
        $post = new Post();
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }
}