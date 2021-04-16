<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Manager\BlogManager;
use Core\controller\Controller;
use Core\controller\Form;
use Core\database\EntityManager;

use Core\http\exceptions\NotFoundException;
use Core\email\Email;
use Core\http\Request;
use App\Repository\PostRepository;
use Core\http\Response;
use Core\http\Session;

class BlogController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $em = $this->getManager();

        $query = 1;
        if ($request->hasQuery('page')) {
            $query = (int)$request->getQuery('page');
        }

        $blogManager = new BlogManager($this->getManager());
        $posts = $blogManager->hydrateListing('created_at', 'DESC', ['page' => $query, 'limit' => 5]);

        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        if (empty($posts)) {
            throw new NotFoundException("pas d'article de blog trouvé", 404);
        }

        $content['posts'] = $posts;

        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }

    public function categoryAction(Request $request, string $category): Response
    {
        $query = 1;
        if ($request->hasQuery('page')) {
            $query = (int)$request->getQuery('page');
        }

        $blogManager = new BlogManager($this->getManager());
        $posts = $blogManager->hydrateListing('created_at', 'DESC', ['page' => $query, 'limit' => 5], $category);
        $content['posts'] = $posts;
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');

        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }
}