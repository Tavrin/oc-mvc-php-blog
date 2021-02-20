<?php


namespace App\src\Controller;


use App\core\controller\Controller;
use App\Core\Http\Request;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {
        $content['title'] = 'Homepage';

        return $this->render('pages/home.html.twig',[
            'content' => $content
        ]);
    }

    public function showAction(Request $request, $slug)
    {
        return $this->render('test.html.twig',[
            'title' => $slug
        ]);
    }
}