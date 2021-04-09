<?php


namespace Core\Event\Events;

use Core\Kernel;
use Core\http\Request;

class RequestEvent
{
    private Kernel $kernel;
    private Request $request;

    /**
     * RequestEvent constructor.
     * @param Kernel $kernel
     * @param Request $request
     */
    public function __construct(Kernel $kernel, Request $request)
    {
        $this->kernel = $kernel;
        $this->request = $request;
    }

    /**
     * @return Kernel
     */
    public function getKernel(): Kernel
    {
        return $this->kernel;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
