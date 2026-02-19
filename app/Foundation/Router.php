<?php

namespace App\Foundation;

class Router
{
    protected array $routes = [];

    public function get(string $uri, string $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
          http_response_code(404);
          \App\Foundation\View\View::render('PageNotFound');
            return;
        }

        [$controller, $method] = explode('@', $action);

        $controllerClass = "App\\Http\\Controllers\\{$controller}";

        $controllerInstance = new $controllerClass();

        $controllerInstance->$method();
    }
}
