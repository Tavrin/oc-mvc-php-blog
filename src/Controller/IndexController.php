<?php


namespace App\src\Controller;


use App\core\controller\Controller;
use App\Core\Http\Request;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {

        $query = $this->entityManager->getConnection()->prepare('SELECT * FROM pofsd');

         $query->execute();
       $result = $query->fetchAll();
        dump($result);
        return $this->render('test.html.twig',[
            'title' => "Twig Title modifié"
        ]);
    }

    public function showAction(Request $request, $slug)
    {
        return $this->render('test.html.twig',[
            'title' => $slug
        ]);
    }
}