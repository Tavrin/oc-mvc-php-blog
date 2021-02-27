<?php


namespace App\src\Controller;

use App\core\controller\Controller;


class ErrorController extends Controller
{
    public function indexAction(\Exception $e = null, string $message = null, int $code = null)
    {
        if (isset($_ENV['ENV']) && $_ENV['ENV'] === 'dev') {
            return $this->render('error.html.twig',[
                'title' => "Page d'erreur",
                'message' => 'test dans le controller',
                'code' => $code
                ]);
        }
        if ($code !== 404) {
            $message = "Le blog a rencontré une erreur !";
            $code = 500;
        }


        return $this->render('error.html.twig',[
            'title' => "Page d'erreur",
            'message' => $message,
            'code' => $code
        ]);
    }
}
