<?php


namespace Core\controller;

use Core\database\EntityManager;
use Core\http\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Core\http\Response;

class Controller
{
    protected const TEMPLATES_DIR = ROOT_DIR . '/templates/';

    protected $twig;

    private $entityManager;

    /**
     * @var Request
     */
    protected $request;

    public $renderContent = null;

    public function __construct(Request $request = null, EntityManager $entityManager = null)
    {
        $this->request = $request;
        $this->entityManager = $entityManager;
        $loader = new FilesystemLoader(self::TEMPLATES_DIR);
        if (isset($_ENV['ENV']) && $_ENV['ENV'] === 'dev') {
            $this->twig = new Environment($loader, [
                'debug' => true
            ]);
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        } else {
            $this->twig = new Environment($loader, [
                'debug' => false
            ]);
        }
    }
    protected function render(string $template = null, array $parameters = [], Response $response = null): Response
    {
        if (empty($this->twig) || empty($template)) {
            throw new \RuntimeException(sprintf('Erreur serveur'), 500);
        }

        if (null === $response) {
            $response = new Response();
        }
        $this->setControllerContent($template, $parameters);

        $response->setContent($this->renderContent);

        return $response;
    }

    public function setControllerContent($template, $parameters)
    {
        $this->renderContent = $this->twig->render($template, $parameters);
    }

    public function getControllerConter()
    {
        return $this->renderContent;
    }

    protected function getManager(): EntityManager
    {
        if (!empty($this->entityManager)) {
            return $this->entityManager;
        } else {
            throw new \RuntimeException("Entity manager is of type : " . gettype($this->entityManager) . " and is called for this controller: " . $this->request->getAttribute('controller'), 500);
        }
    }

    protected function get404()
    {
        header("location:/error");
        exit();
    }
}
