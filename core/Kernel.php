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
    public $entityManager;

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
        if (null === $this->dispatcher) {
            $this->setDispatcher();
        }


        $dispatcher = $this->dispatcher;
        $this->listenerService = new ListenerService($dispatcher);
        $this->listenerService->setListeners();
        $this->argumentResolver = new ArgumentsResolver();

        $this->entityManager = DatabaseResolver::instanciateManager();
        $this->controllerResolver = new ControllerResolver();


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
    public function handleRequest(Request $request)
    {
        try {
            return $this->route($request);
        } catch (\Exception $e) {
            return $this->throwResponse($e, $request);
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

        return $response;
    }

    public function throwResponse(\Throwable $e): Response
    {
        $controller = Router::matchError($e);
        $response = ControllerResolver::createController($controller);
        $message = $e->getMessage();
        $code = $e->getCode();
         return $response($e, $message, $code);
    }
}
