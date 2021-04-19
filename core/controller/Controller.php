<?php


namespace Core\controller;

use Core\database\EntityManager;
use Core\http\Request;
use Core\http\Session;
use Core\security\Security;
use Core\utils\JsonParser;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Core\http\Response;

class Controller
{
    protected const TEMPLATES_DIR = ROOT_DIR . '/templates/';
    protected const CONFIG_DIR = ROOT_DIR . '/config/';
    protected Environment $twig;

    private ?EntityManager $entityManager;

    protected Session $session;

    /**
     * @var Request|null
     */
    protected ?Request $request;

    /**
     * @var Security
     */
    protected Security $security;

    public $renderContent = null;

    public function __construct(Request $request = null, EntityManager $entityManager = null)
    {
        $this->security = new Security();
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->session = new Session();
        $this->session->start();
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
        $parameters['flash'] = $this->session->getAllFlash();
        if ($this->session->has('user')) {
            $parameters['user'] = $this->session->get('user');
        }

        $parameters['app'] = $this->getConstants();
        $parameters['app']['path'] = $this->request->getAttribute('breadcrumb');
        $this->setControllerContent($template, $parameters);

        $response->setContent($this->renderContent);

        return $response;
    }

    public function setControllerContent($template, $parameters = []): string
    {
        return $this->renderContent = $this->twig->render($template, $parameters);
    }

    public function getControllerContent()
    {
        return $this->renderContent;
    }

    public function getConstants(): array
    {
        $constants = [];
        if ($configConstants = JsonParser::parseFile(self::CONFIG_DIR . '/constants.json')) {
            $constants['userConstants'] = $configConstants;
        }

        if ($this->request) {
            $constants['constants']['host'] = $this->request->getHost();
        }

        return $constants;
    }

    public function sendJson(array $data, int $status = 200): Response
    {
        $response = new Response();
        $response->setJsonContent($data, $status);

        return $response;
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

    protected function getUser()
    {
        return $this->security->getUser();
    }

    /**
     * @param string $path
     * @param array|null[] $flash
     */
    protected function redirect(string $path, array $flash = ['type' => null, 'message' => null])
    {
        if (isset($flash['type']) && isset($flash['message'])) {
            $this->flashMessage($flash['type'], $flash['message']);
        }
        header("Location:" . $path);
        exit();
    }

    /**
     * @param object $entity
     * @param array $options
     * @return Form
     */
    protected function createForm(object $entity, array $options = []): Form
    {
        return new Form($this->request, $entity, $this->session, $options);
    }

    protected function flashMessage(string $key, string $message)
    {
        $this->session->addFlash($key, $message);
    }
}
