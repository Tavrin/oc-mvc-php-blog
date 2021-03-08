<?php

namespace App\Controller;

use App\Entity\Post;
use Core\controller\Controller;
use Core\database\EntityManager;
use Core\http\Request;
use App\Repository\PostRepository;
use Core\http\Response;

class BlogController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $em = $this->getManager();
        $post = new PostRepository($em);
        $posts = $post->findAll();
        dd($posts);

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