<?php

namespace Core;

use Core\database\DatabaseResolver;
use Core\database\EntityManager;
use Core\http\Request;
use Core\http\Response;
use Core\Event\Dispatcher;
use Core\Event\EventNames;
use Core\Event\ListenerService;
use Core\Event\Events\RequestEvent;
use Core\Event\Events\ControllerEvent;
use Core\controller\ControllerResolver;
use Core\controller\ArgumentsResolver;
use Core\routing\Router;
use Core\utils\JsonParser;


/**
 * Class Kernel
 * @package App\core
 */
class Kernel
{
    /**
     * @var ?Dispatcher
     */
    protected ?Dispatcher $dispatcher = null;

    /**
     * @var ListenerService
     */
    protected ListenerService $listenerService;

    /**
     * @var ControllerResolver
     */
    protected ControllerResolver $controllerResolver;

    public ?EntityManager $entityManager = null;

    /**
     * @var ArgumentsResolver
     */
    protected ArgumentsResolver $argumentResolver;

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
        $code === 404 ? $controllerResponse->setStatusCode(404):$controllerResponse->setStatusCode(500);
        $controllerResponse->send();
        exit();
    }
}
