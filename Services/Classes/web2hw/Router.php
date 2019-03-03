<?php

namespace web2hw;

class Router
{
    private $routesFile;
    private $routes;
    private $controllersPath = __DIR__ . '/../../../Controllers';

    public function __construct($routesFile)
    {
        $this->routesFile = $routesFile;
        $this->routes = $this->getRoutes();

        $this->checkRoute();
    }

    /**
     * Get routes from routes file
     *
     * @return array
     */
    private function getRoutes()
    {
        $routes = @file_get_contents($this->routesFile) or die("Unable to open routes file!");
        return json_decode($routes, 1);
    }

    /**
     * Check if current request url math with
     */
    private function checkRoute()
    {
        $request = new Request();
        $requestUrl = $request->getPath();
        $requestMethod = $request->getMethod();
        $routes = $this->routes;
        $controllersPath = $this->controllersPath;

        foreach ($routes as $route) {
            $path = $route['path'];
            $method = strtoupper($route['method']);

            if ($path == $requestUrl && $method == $requestMethod) {
                $action = explode('::', $route['action']);
                $controller = explode('\\', $action[0]);
                $controllerFile = $controllersPath . '/' . $controller[1] . '.php';

                if (file_exists($controllerFile))
                    require_once $controllerFile;
                else
                    die ('Controller ' . $action[0] . ' not found!');

                $object = new $action[0]();
                $objMethod = (string)$action[1];

                if (!method_exists($object, $objMethod))
                    die ('Method ' . $objMethod . ' not found in controller ' . $action[0]);

                $object->$objMethod();
            }
        }
    }
}