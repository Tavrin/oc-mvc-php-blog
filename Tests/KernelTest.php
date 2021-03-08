<?php


namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Core\Kernel;
use Core\http\Request;
use Core\Event\Dispatcher;
use Core\event\EventNames;
use Core\event\ListenerService;
use Core\Event\Events\RequestEvent;

class KernelTest extends TestCase
{
    /**
     * @var Kernel
     */
    private $entity;
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var ListenerService
     */
    private $listenerService;

    public function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->dispatcher = $this->createMock(Dispatcher::class);
        $this->$this->listenerService = $this->createMock(ListenerService::class);

        $this->entity = new Kernel();
    }

}