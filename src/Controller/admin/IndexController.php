<?php

namespace App\Controller\admin;

use App\Manager\AdminManager;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Core\controller\Controller;
use Core\http\Request;
use Core\http\Response;

class IndexController extends Controller
{
    public function indexAction(): Response
    {
        $adminManager = new AdminManager();
        $postRepository = new PostRepository($this->getManager());
        $userRepository = new UserRepository($this->getManager());
        $commentRepository = new CommentRepository($this->getManager());

        $content = $adminManager->hydrateDashboard($postRepository, $userRepository, $commentRepository);

        return $this->render('admin/index.html.twig', [
            'content' => $content
        ]);
    }

    public function structureAction(Request $request): Response
    {
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        return $this->render('admin/structure.html.twig',
        [
            'content' => $content
        ]);
    }
}