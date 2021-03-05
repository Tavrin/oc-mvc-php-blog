<?php


namespace App\Controller;

use Core\controller\Controller;
use Core\http\Response;


class ErrorController extends Controller
{
    /**
     * @param \Exception|null $e
     * @param string|null $message
     * @param int|null $code
     * @return Response
     */
    public function indexAction(\Exception $e = null, string $message = null, int $code = null):Response
    {
        $content['title'] = "Page d'erreur";

        if (isset($_ENV['ENV']) && $_ENV['ENV'] === 'dev') {
            $content['code'] = $code;
            $content['message'] = $message;

            return $this->render('error.html.twig',[
                'content' => $content
                ]);
        }

        404 === $code ? $message = "La page demandée n'existe pas":($message = "Le blog a rencontré une erreur" AND $code = 500);
        $content['code'] = $code;
        $content['message'] = $message;
        return $this->render('error.html.twig',[
            'content' => $content
        ]);
    }
}
