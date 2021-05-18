<?php

namespace Core;

use Core\controller\ControllerException;
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
use Exception;
use RuntimeException;
use Throwable;


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

    private ?Request $request = null;

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
        } catch (Exception $e) {
            $this->throwResponse($e);
        }

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
        $this->request = $request;
        try {
            return $this->route($request);
        } catch (Exception $e) {
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

        $response = $controller(...$arguments);

        if (!$response instanceof Response) {
            throw new ControllerException('No response sent back from controller', 500);
        }

        return $response;
    }

    public function throwResponse(Throwable $e)
    {
        $controller = Router::matchError();
        if (!$this->request) {
            $this->request = Request::create();
        }

        $options['entityManager'] = false;
        $controller = ControllerResolver::createController($controller, $this->request, $this->entityManager);
        $controllerResponse = $controller($e);
        $e->getCode() === 404 ? $controllerResponse->setStatusCode(404):$controllerResponse->setStatusCode(500);
        $controllerResponse->send();
        exit();
    }
}
