<?php


namespace App\src\Controller\admin;

use App\core\controller\Controller;
use App\Core\Http\Request;
use App\src\Entity\Post;

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