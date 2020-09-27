<?php

namespace ZnCrypt\BaseTunnel\Symfony4\Api;

use Doctrine\DBAL\Connection;
use Illuminate\Database\Capsule\Manager as CapsuleManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use ZnCore\Base\Enums\Http\HttpMethodEnum;
use ZnLib\Db\Facades\DoctrineFacade;
use ZnLib\Db\Capsule\Manager;
use ZnCrypt\BaseTunnel\Symfony4\Api\Controllers\HandShakeController;
use ZnLib\Rest\Symfony4\Helpers\RestApiRouteHelper;

class CryptModule
{

    public function getRouteCollection(RouteCollection $routeCollection)
    {
        $endpoint = 'v1/crypt-handshake';
        $controllerClassName = HandShakeController::class;
        $routeNamePrefix = RestApiRouteHelper::extractRoutePrefix($controllerClassName);

        //$this->addRoute($routeCollection, $controllerClassName, 'getPublicKey');
        $this->addRoute($routeCollection, $controllerClassName, 'setSecretKey');
        $this->addRoute($routeCollection, $controllerClassName, 'startSession');

        //RestApiRouteHelper::defineCrudRoutes('v1/crypt-handshake', HandShakeController::class, $routeCollection);
    }

    private function addRoute(RouteCollection $routeCollection, $controllerClassName, $actionName, array $methods = [HttpMethodEnum::GET, HttpMethodEnum::POST])
    {
        $endpoint = 'v1/crypt-handshake';
        //$controllerClassName = HandShakeController::class;
        $routeNamePrefix = RestApiRouteHelper::extractRoutePrefix($controllerClassName);

        $defaults = [
            '_controller' => $controllerClassName,
            '_action' => $actionName,
        ];
        $routeName = $routeNamePrefix . '_' . $actionName;
        $route = new Route($endpoint . '/' . $actionName, $defaults, [], [], null, [], $methods);
        $routeCollection->add($routeName, $route);

    }

    public function bindContainer(ContainerInterface $container)
    {
        $container->bind(CapsuleManager::class, Manager::class);
        $container->bind(Connection::class, function () {
            return DoctrineFacade::createConnection();
        });
        /*$container->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $container->bind(TagRepositoryInterface::class, TagRepository::class, true);
        $container->bind(TagPostRepositoryInterface::class, TagPostRepository::class);
        $container->bind(PostRepositoryInterface::class, PostRepository::class);
        $container->bind(PostServiceInterface::class, PostService::class);*/
    }

}
