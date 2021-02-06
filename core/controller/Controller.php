<?php


namespace App\core\controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Core\Http\Response;

class Controller
{
    protected const TEMPLATES_DIR = ROOT_DIR . '/templates/';
    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(self::TEMPLATES_DIR);
        $this->twig = new Environment($loader);
    }
    protected function render(string $template = null, array $parameters = [], Response $response = null): Response
    {
        if (empty($this->twig) || empty($template)) {
            throw new \RuntimeException(sprintf('Erreur serveur'), 500);
        }

        if (null === $response) {
            $response = new Response();
        }

        $content = $this->twig->render($template, $parameters);
        $response->setContent($content);

        return $response;
    }
}
