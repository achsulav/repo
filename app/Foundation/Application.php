<?php

namespace App\Foundation;

class Application
{
    protected Router $router;

    public function __construct()
    {
        $this->router = new Router();
        // Register routes here
        $this->router->get('/', 'PageController@home');
        $this->router->get('/login', 'PageController@login');
        $this->router->get('/test','PageController@testRedirect');
    }


    public function run()
    {
        $this->router->resolve();
    }
}
