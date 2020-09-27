<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use Illuminate\Container\Container;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnLib\Rest\Helpers\CorsHelper;
use ZnLib\Rest\Symfony4\Helpers\RestApiControllerHelper;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../../common/Bootstrap/autoload.php';
DotEnv::init(__DIR__ . '/../..');

CorsHelper::autoload();

$container = Container::getInstance();
$routeCollection = new RouteCollection;

require_once __DIR__ . '/../../vendor/znbundle/crypt-tunnel/src/Symfony/config/bootstrap.php';

$response = RestApiControllerHelper::run($routeCollection, $container);
$response->send();
