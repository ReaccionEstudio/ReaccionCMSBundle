<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class RoutesCollection
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model
 */
class RoutesCollection extends ArrayCollection
{
    /**
     * Main route
     * @var Route $mainRoute
     */
    private $mainRoute = null;

    /**
     * @return null
     */
    public function getMainRoute() : ?Route
    {
        return $this->mainRoute;
    }

    /**
     * @param null $mainRoute
     */
    public function setMainRoute(Route $mainRoute): void
    {
        $this->mainRoute = $mainRoute;
    }
}
