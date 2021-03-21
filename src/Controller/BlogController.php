<?php

namespace App\Controller;

use App\Entity\Post;
use Core\controller\Controller;
use Core\database\EntityManager;
use Core\http\exceptions\NotFoundException;
use Core\http\Request;
use App\Repository\PostRepository;
use Core\http\Response;
use Core\http\Session;

class BlogController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $em = $this->getManager();

        $post = new PostRepository($em);
        $posts = $post->findAll();
        $this->flashMessage('success', 'test2');
        $this->flashMessage('succaaaess', 'test3');
        $this->flashMessage('danger', 'test4');

        $this->flashMessage('warning', 'test5');

        $this->redirect('/', ['type' =>'success', 'message' => 'test flash']);
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        if (empty($posts)) {
            throw new NotFoundException("pas d'article de blog trouvé", 404);
        }

        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }
}