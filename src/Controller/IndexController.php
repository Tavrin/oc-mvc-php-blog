<?php


namespace App\Controller;


use Core\controller\Controller;
use Core\http\Request;
use Core\http\Response;

class IndexController extends Controller
{
    public function indexAction(Request $request): Response
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