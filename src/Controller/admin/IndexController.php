<?php

namespace App\Controller\admin;

use Core\controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }
}