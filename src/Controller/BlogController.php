<?php

namespace App\Controller;

use App\Entity\Post;
use Core\controller\Controller;
use Core\database\EntityManager;
use Core\http\Request;
use App\Repository\PostRepository;

class BlogController extends Controller
{
    public function indexAction(Request $request)
    {
        EntityManager::getAllEntityData();
        $em = $this->getManager();
        $postRepo = new PostRepository($em);
        $posts = $postRepo->findAll();

        $content['posts'] = $posts;
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        if (empty($posts)) {
            throw new \RuntimeException("pas d'article de blog trouvé","500");
        }

        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }
}