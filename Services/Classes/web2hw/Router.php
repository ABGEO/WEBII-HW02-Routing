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
        $routeFound = false;

        foreach ($routes as $route) {
            $path = $this->convertToRE($route['path']);
            $method = strtoupper($route['method']);
            $match = array();

            if ($requestMethod == $method && preg_match($path, $requestUrl, $match)) {
                $routeFound = true;
                unset($match[0]);

                $action = explode('::', $route['action']);
                $controller = explode('\\', $action[0]);
                $controllerFile = $controllersPath . '/' . $controller[1] . '.php';

                //Include controller file
                if (file_exists($controllerFile))
                    require_once $controllerFile;
                else
                    die ('Controller ' . $action[0] . ' not found!');

                //Get controllers new object
                $object = new $action[0]();

                $objMethod = (string)$action[1];

                if (!method_exists($object, $objMethod))
                    die ('Method ' . $objMethod . ' not found in controller ' . $action[0]);

                if (empty($match))
                    $object->$objMethod();
                else
                    $object->$objMethod($match);
            }
        }

        if (!$routeFound) {
            $response = new Response("Route {$requestUrl} Not Found!", 404);
            echo $response->sendResponse();
        }
    }

    /**
     * Convert route to regular expression
     *
     * @param $plainText
     * @return string
     */
    private function convertToRE($plainText)
    {
        $plainText = str_replace('/', "\/", $plainText);

        $lastMatch = 0;
        while ($start = strpos($plainText, '{', $lastMatch)) {
            $end = strpos($plainText, '}', $lastMatch);

            //Cut tet for replacing
            $changeMe = substr($plainText, $start, $end - $start + 1);

            $reName = substr($changeMe, 1, strlen($changeMe) - 2);

            $replace = "(?<{$reName}>[a-zA-Z0-9\_\-]+)";

            $plainText = str_replace($changeMe, $replace, $plainText);

            $lastMatch = $start + 1;
        }

        return "@^{$plainText}$@D";
    }
}