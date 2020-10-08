<?php

use Illuminate\Container\Container;
use ZnCrypt\Pki\Domain\Libs\Rsa\RsaStoreFile;
use ZnCrypt\Tunnel\Symfony4\Api\CryptModule;
use ZnCore\Base\Enums\Measure\TimeEnum;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use ZnLib\Rest\Symfony4\Helpers\RestApiControllerHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnCrypt\Pki\Domain\Libs\Rsa\RsaStoreInterface;

/**
 * @var Container $container
 * @var RouteCollection $routeCollection
 */

$articleModule = new CryptModule;
$articleModule->bindContainer($container);
$articleModule->getRouteCollection($routeCollection);

$container->bind(Request::class, function () {
    $request = Request::createFromGlobals();
    RestApiControllerHelper::prepareContent($request);
    return $request;
}, true);
$container->bind(RsaStoreInterface::class, function () {
    $rsaDirectory = FileHelper::rootPath() . '/' . $_ENV['RSA_HOST_DIRECTORY'];
    return new RsaStoreFile($rsaDirectory);
}, true);
$container->bind(AbstractAdapter::class, function () {
    $cacheDirectory = FileHelper::rootPath() . '/' . $_ENV['CACHE_DIRECTORY'];
    return new FilesystemAdapter('cryptoSession', TimeEnum::SECOND_PER_DAY, $cacheDirectory);
}, true);
