<?php


namespace App\src\Controller;

use App\core\controller\Controller;


class ErrorController extends Controller
{
    public function indexAction(string $message, int $code)
    {

        if ($code !== 404) {
            $message = "Le blog a rencontré une erreur serveur !";
            $code = 500;
        }

        return $this->render('error.html.twig',[
            'title' => "Page d'erreur",
            'message' => $message,
            'code' => $code
        ]);
    }
}
