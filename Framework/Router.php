<?php

namespace Framework;

use App\controllers\ErrorController;
use Framework\middleware\Authorize;

class Router
{
    /**
     * An array to store the routes
     * @var array $route
     */

    protected $routes = [];

    /**
     * Dynamically get the request method, url and controller for the Routes methods
     * @param string $method
     * @param string $uri
     * @param array $middleware
     * @param string $action
     */

    public function registerRoute($method, $uri, $action, $middleware = [])
    {
        list($controller, $controllerMethod) = explode('@', $action);
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'middleware' => $middleware
        ];
    }

    /**
     * A method to add a GET request to the routes array
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * return void
     */

    public function get($uri, $controller, $middleware = [])
    {
        $this->registerRoute('GET', $uri, $controller, $middleware);
    }

    /**
     * A method to add a POST request to the routes array
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * return void
     */

    public function post($uri, $controller, $middleware = [])
    {
        $this->registerRoute('POST', $uri, $controller, $middleware);
    }

    /**
     * A method to add a PUT request to the routes array
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * return void
     */

    public function put($uri, $controller, $middleware = [])
    {
        $this->registerRoute('PUT', $uri, $controller, $middleware);
    }

    /**
     * A method to add a DELETE request to the routes array
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * return void
     */

    public function delete($uri, $controller, $middleware = [])
    {
        $this->registerRoute('DELETE', $uri, $controller, $middleware);
    }

    /**
     * A method that loops through the this->routes array and checks if the request method and uri match
     * @param $method
     * @param $uri
     * return void
     */

    public function route($uri)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            $uriSegments = explode('/', trim($uri, '/'));

            $routeUriSegments = explode('/', trim($route['uri'], '/'));
            $match = true;

            if (count($uriSegments) === count($routeUriSegments) && strtoupper($route['method']) === $requestMethod) {
                $params = [];
                for ($i = 0; $i < count($uriSegments); $i++) {
                    if ($routeUriSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeUriSegments[$i])) {
                        $match = false;
                        break;
                    }

                    if (preg_match('/\{(.+?)\}/', $routeUriSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }
                if ($match) {
                    foreach ($route['middleware'] as $middleware) {
                        $authorize = new Authorize();
                        $authorize->hasRole($middleware);
                    }
                    $controller = "App\\controllers\\{$route['controller']}";
                    $controllerMethod = $route['controllerMethod'];

                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    return;
                }
            }
        }

        ErrorController::notFound();
    }
}
