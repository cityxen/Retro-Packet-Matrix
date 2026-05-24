<?php

class Router {
    private array $routes = [];

    public function get(string $path, array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $uri, string $method): void {
        $uri = rtrim($uri, '/') ?: '/';

        if (isset($this->routes[$method][$uri])) {
            [$class, $action] = $this->routes[$method][$uri];
            (new $class())->$action();
            return;
        }

        http_response_code(404);
        $view = new View();
        $view->render('errors/404', ['uri' => $uri]);
    }
}
