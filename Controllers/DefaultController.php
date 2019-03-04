<?php

namespace web2hw;

class DefaultController
{
    public function index()
    {
        $response = new Response('<h1>Hello</h1>');
        echo $response->sendResponse();
    }

    public function about()
    {
        echo 'About';
    }

    public function routes()
    {
        $routes = @file_get_contents(__DIR__ . '/../routes.json');

        $response = new Response($routes, 200, ['Content-Type' => 'application/json']);
        echo $response->sendResponse();
    }

    public function sayHello($args)
    {
        echo "Hello, I'm {$args['name']} {$args['surname']}";
    }
}