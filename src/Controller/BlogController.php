<?php

namespace App\Controller;

use Core\controller\Controller;
use Core\http\Request;
use App\Repository\PostRepository;

class BlogController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getManager();
        $posts = new PostRepository($em);
        $post = $posts->findOneBy('id',45687 );
        $content['post'] = $post[0];
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        if (empty($post = $posts->findAll())) {
            throw new \RuntimeException("pas d'article de blog trouvé","500");
        }
        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }
}