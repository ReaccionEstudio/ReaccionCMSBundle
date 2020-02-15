<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router;

use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\Route;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\RoutesCollection;

/**
 * Class RouteCollectionBuilder
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router
 */
class RouteCollectionFactory
{
    /**
     * @param array $routes
     * @return RoutesCollection
     */
    public static function createCollection(array $routes = []) : RoutesCollection
    {
        $routesCollection = new RoutesCollection();

        if(count($routes) === 0) {
            return $routesCollection;
        }

        foreach($routes as $routeData) {
            $isMainPage = ($routeData['main_page'] === '1') ? true : false;

            $route = new Route();
            $route->setName($routeData['name']);
            $route->setSlug($routeData['slug']);
            $route->setLanguage($routeData['language']);
            $route->setTemplate($routeData['template']);
            $route->setIsMainPage($isMainPage);

            $routesCollection->add($route);

            if(true === $isMainPage) {
                $routesCollection->setMainRoute($route);
            }
        }

        return $routesCollection;
    }
}
