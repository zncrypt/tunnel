<?php

namespace PhpBundle\CryptTunnel\Symfony\Api;

use Doctrine\DBAL\Connection;
use Illuminate\Database\Capsule\Manager as CapsuleManager;
use PhpBundle\Article\Domain\Interfaces\CategoryRepositoryInterface;
use PhpBundle\Article\Domain\Interfaces\PostRepositoryInterface;
use PhpBundle\Article\Domain\Interfaces\PostServiceInterface;
use PhpBundle\Article\Domain\Interfaces\TagPostRepositoryInterface;
use PhpBundle\Article\Domain\Interfaces\TagRepositoryInterface;
use PhpBundle\Article\Domain\Repositories\Doctrine\PostRepository;
use PhpBundle\Article\Domain\Repositories\Eloquent\CategoryRepository;
use PhpBundle\Article\Domain\Repositories\Eloquent\TagPostRepository;
use PhpBundle\Article\Domain\Repositories\Eloquent\TagRepository;
use PhpBundle\Article\Domain\Services\PostService;
use PhpBundle\CryptTunnel\Symfony\Api\Controllers\HandShakeController;
use PhpLab\Core\Enums\Http\HttpMethodEnum;
use PhpLab\Eloquent\Db\Helpers\DoctrineHelper;
use PhpLab\Eloquent\Db\Helpers\Manager;
use PhpLab\Rest\Helpers\RestApiRouteHelper;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

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

    private function addRoute(RouteCollection $routeCollection, $controllerClassName, $actionName, array $methods = [HttpMethodEnum::GET, HttpMethodEnum::POST]) {
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
            return DoctrineHelper::createConnection();
        });
        /*$container->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $container->bind(TagRepositoryInterface::class, TagRepository::class, true);
        $container->bind(TagPostRepositoryInterface::class, TagPostRepository::class);
        $container->bind(PostRepositoryInterface::class, PostRepository::class);
        $container->bind(PostServiceInterface::class, PostService::class);*/
    }

}
