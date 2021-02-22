<?php

namespace App\src\Controller;

use App\core\controller\Controller;
use App\Core\Http\Request;

class BlogController extends Controller
{
    public function indexAction(Request $request)
    {
        dd($this->entityManager->findBy('post', 'id', '45687'));
        $content = [];
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        $this->entityManager->findAll('post');
        return $this->render('blog/index.html.twig',[
            'content' => $content ? $content:null
        ]);
    }
}