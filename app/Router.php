<?php

namespace App;

class Router
{
    protected $routes = [];

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function resolve($uri, $method)
    {

        // Match the route with parameters
        $routeFound = false;
        $params = [];

        foreach ($this->routes[$method] as $route => $controllerAction) {
            // Create a regular expression for the route
            $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route);
            if (preg_match("#^$pattern$#", $uri, $matches)) {
                array_shift($matches); // Remove the full match
                $params = $matches; // Get the parameters
                $routeFound = true;
                break;
            }
        } 
        if ($routeFound) {
            list($controller, $action) = explode('@', $controllerAction);
            $controllerClass = "App\\Controllers\\{$controller}";
            $controllerInstance = new $controllerClass();
            return $controllerInstance->$action(...$params);
        } else {
            echo "404 - Page not found"; 
        }
    }

    public function viewRoutes()
    {   echo "<pre>";
        print_r($this->routes);
        echo "</pre>";
    }
}
