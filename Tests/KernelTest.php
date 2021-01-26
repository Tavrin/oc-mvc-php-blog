<?php


namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\core\Kernel;
use App\Core\Http\Request;
use App\Core\Event\Dispatcher;
use App\core\event\EventNames;
use App\core\event\ListenerService;
use App\core\event\events\RequestEvent;

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