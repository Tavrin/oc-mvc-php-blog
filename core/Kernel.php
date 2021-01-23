<?php

namespace App\core;

use App\Core\Http\Request;
use App\Core\Event\Dispatcher;
use App\core\event\EventNames;

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


    public function __construct()
    {
        $this->dispatcher = new Dispatcher();
    }

    /**
     * @param Request $request
     */
    public function route(Request $request)
    {
        $this->dispatcher->dispatch($request,EventNames::REQUEST);
    }

}