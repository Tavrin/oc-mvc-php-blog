<?php

namespace App\src\Controller;

use App\core\controller\Controller;
use App\Core\Http\Request;
use http\Exception\RuntimeException;

class BlogController extends Controller
{
    public function indexAction(Request $request)
    {

        $content = [];
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        if (empty($post = $this->getManager()->findAll('post'))) {
            throw new \RuntimeException("pas d'article de blog trouvé","500");
        }
        return $this->render('blog/index.html.twig',[
            'content' => $post
        ]);
    }
}