<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Illuminate\Container\Container;
use PhpLab\Core\Libs\Env\DotEnvHelper;
use PhpLab\Rest\Helpers\CorsHelper;
use PhpLab\Rest\Helpers\RestApiControllerHelper;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../../common/Bootstrap/autoload.php';
DotEnvHelper::init(__DIR__ . '/../..');

CorsHelper::autoload();

$container = Container::getInstance();
$routeCollection = new RouteCollection;

require_once __DIR__ . '/../../vendor/php7bundle/crypt-tunnel/src/Symfony/config/bootstrap.php';

$response = RestApiControllerHelper::run($routeCollection, $container);
$response->send();
