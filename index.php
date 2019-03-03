<?php

require_once __DIR__ . '/Services/autoloader.php';

use web2hw\Router;

define('ROUTES', __DIR__ . '/routes.json');
$router = new Router(ROUTES);