<?php


namespace App\src\Controller\admin;

use App\core\controller\Controller;

class BlogController extends Controller
{
    public function indexAction()
    {
        $this->getManager()->findAll('post');
    }

    public function showAction()
    {

    }

    public function newAction()
    {

    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }
}