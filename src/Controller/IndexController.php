<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use Core\controller\Controller;
use Core\http\Request;
use Core\http\Response;

class IndexController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $categoryRepository = new CategoryRepository($this->getManager());
        $content['categories'] = $categoryRepository->findAll();
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