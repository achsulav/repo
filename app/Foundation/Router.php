<?php

namespace App\Foundation;

class Router
{
    protected array $routes = [];

    public function get(string $uri, string $action, array $middlewares = [])
    {

      $this->routes['GET'][$uri] = [
        'action' => $action,
        'middlewares' => $middlewares
      ];
    }

    public function post(string $uri, string $action,array $middlewares = [])
    {
      $this->routes['POST'][$uri] = [
        'action' => $action,
        'middlewares' => $middlewares
      ];
    }
    public function resolve()
    {
        $requestmethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $subdomain = Application::$app->getSubdomain();

        // If on a user subdomain, root path '/' and single-part slugs should go to user profile
        // unless they are explicitly registered global routes that should be accessible on subdomains.
        if ($subdomain && $subdomain !== 'blogify' && $subdomain !== 'www') {
            // Check if it's the root path or a path that doesn't match a formal route
            if ($uri === '/' || !isset($this->routes[$requestmethod][$uri])) {
                // However, we still want things like /logout or /dashboard to work if they are on the subdomain
                // but we only want them to work if they are AUTH routes.
                // For simplicity, let's say if it's '/' on a subdomain, it's ALWAYS the profile.
                if ($uri === '/') {
                    $controller = new \App\Http\Controllers\UserController();
                    return $controller->profile($subdomain, null);
                }
            }
        }

        $routes = $this->routes[$requestmethod] ?? [];

        foreach($routes as $routeUri => $routeData){

          $pattern = preg_replace('#\{[^/]+\}#' , '([^/]+)', $routeUri);
          $pattern = "#^$pattern$#";
          if(preg_match($pattern,$uri,$matches)){
            array_shift($matches);
            $action = $routeData['action'] ?? $routeData;

              //Run Middlewares
            if(is_array($routeData)){
              foreach($routeData['middlewares'] ?? [] as $middleware){
                $middlewareClass = "App\\Http\\Middleware\\" . ucfirst($middleware) . "Middleware";
                (new $middlewareClass())->handle();
              }
            }
            //Run Controller Action
            [$controller,$method] = explode('@',$action);
            $controllerClass = "App\\Http\\Controllers\\{$controller}";
            $controllerInstance = new $controllerClass();
            return $controllerInstance->$method(...$matches);
          }
          }

          http_response_code(404);
          \App\Foundation\View\View::render('PageNotFound', ['blogOwner' => null]);
          exit;
        }
}

 /*       
       if (!$route) {
          http_response_code(404);
          \App\Foundation\View\View::render('PageNotFound');
            return;
        }

        foreach ($route['middlewares'] as $middleware) {
          $middlewareClass = "App\\Http\\Middleware\\" . ucfirst($middleware) . "Middleware";
          $middlewareInstance = new $middlewareClass();
          $middlewareInstance->handle();
        }

        [$controller, $method] = explode('@', $route['action']);

        $controllerClass = "App\\Http\\Controllers\\{$controller}";

        $controllerInstance = new $controllerClass();

        $controllerInstance->$method();
    }
}
  */
