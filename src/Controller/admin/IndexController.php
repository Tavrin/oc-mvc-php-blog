<?php

namespace App\Controller\admin;

use App\Manager\AdminManager;
use Core\controller\Controller;
use Core\http\Response;

class IndexController extends Controller
{
    public function indexAction(): Response
    {
        $adminManager = new AdminManager();
        $content = $adminManager->hydrateDashboard();

        return $this->render('admin/index.html.twig', [
            'content' => $content
        ]);
    }
}