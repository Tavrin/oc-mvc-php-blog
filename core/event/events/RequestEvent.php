<?php


namespace App\core\event\events;

use App\core\Kernel;
use App\Core\Http\Request;

class RequestEvent
{
    private $kernel;
    private $request;

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
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
