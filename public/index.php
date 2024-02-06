<?php
// session_start();
require __DIR__ . '/../vendor/autoload.php';

use Framework\Router;
use Framework\Session;

// Start the session
Session::start();

require '../helpers.php';

$router = new Router();

$routes = require baseUrl('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->route($uri);
