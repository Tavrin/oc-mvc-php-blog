<?php

namespace App\src\Controller;

use App\core\controller\Controller;
use App\Core\Http\Request;
use App\src\Repository\PostRepository;

class BlogController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getManager();
        $posts = new PostRepository($em);

        $content = [];
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        if (empty($post = $posts->findAll())) {
            throw new \RuntimeException("pas d'article de blog trouvé","500");
        }
        return $this->render('blog/index.html.twig',[
            'content' => $post
        ]);
    }
}