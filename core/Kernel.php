<?php

namespace App\core;

use App\core\database\DatabaseResolver;
use App\core\database\EntityManager;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Event\Dispatcher;
use App\core\event\EventNames;
use App\core\event\ListenerService;
use App\core\event\events\RequestEvent;
use App\core\event\events\ControllerEvent;
use App\core\controller\ControllerResolver;
use App\core\controller\ArgumentsResolver;
use App\core\routing\Router;
use App\core\utils\JsonParser;
use http\Exception\RuntimeException;
use function PHPUnit\Framework\throwException;


/**
 * Class Kernel
 * @package App\core
 */
class Kernel
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var ListenerService
     */
    protected $listenerService;

    /**
     * @var ControllerResolver
     */
    protected $controllerResolver;

    /**
     * @var EntityManager
     */
    public $entityManager = null;

    /**
     * @var ArgumentsResolver
     */
    protected $argumentResolver;

    public function __construct()
    {
        $this->setServices();
    }

    public function setServices()
    {
        try {
            if (null === $this->dispatcher) {
                $this->setDispatcher();
            }


            $dispatcher = $this->dispatcher;
            $this->listenerService = new ListenerService($dispatcher);
            $this->listenerService->setListeners();
            $this->argumentResolver = new ArgumentsResolver();
            $this->entityManager = DatabaseResolver::instantiateManager();
            $this->controllerResolver = new ControllerResolver();
        } catch (\Exception $e) {
            $this->throwResponse($e);
        }

    }

    private function setDatabase()
    {

    }

    public function setDispatcher()
    {
        $this->dispatcher = new Dispatcher();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handleRequest(Request $request):Response
    {
        try {
            return $this->route($request);
        } catch (\Exception $e) {
            $this->throwResponse($e);
        }
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function route(Request $request):Response
    {
        $event = new RequestEvent($this, $request);
        $this->dispatcher->dispatch($event, EventNames::REQUEST);

        $controller = $this->controllerResolver->getController($request, $this->entityManager);

        $event = new ControllerEvent($this, $controller);
        $this->dispatcher->dispatch($event, EventNames::CONTROLLER);

        $arguments = $this->argumentResolver->getArguments($request, $controller);

       /* $this->argumentResolver->getArguments($request, $controller); */
        $response = $controller(...$arguments);

        if (!$response instanceof Response) {
            throw new \RuntimeException(sprintf('Mauvaise réponse'), 500);
        }

        return $response;
    }

    public function throwResponse(\Throwable $e)
    {
        $controller = Router::matchError($e);
        $controller = ControllerResolver::createController($controller);
        $message = $e->getMessage();
        $code = $e->getCode();
        $controllerResponse = $controller($e, $message, $code);
        $response = new Response();
        $response->setContent($controllerResponse->content);
        $response->send();
        exit();
    }
}
