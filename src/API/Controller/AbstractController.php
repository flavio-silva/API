<?php

namespace API\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AbstractController
{
    /**
     * @var Request
     */
    protected $request;
    
    /**
     * @var Application
     */
    protected $app;
    
    protected $service;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }
    
    
}
