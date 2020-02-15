<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions\CannotLoadRoutesException;

/**
 * Class FileLoader
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Components\Router\Loader
 */
class FileLoader implements LoaderInterface
{
    /**
     * @var array $routes
     */
    private $routes;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * FileLoader constructor.
     * @param EntityManagerInterface $em
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->em = $em;
        $this->routes = [];
    }

    /**
     * Loads routes from a system file.
     */
    public function load() : LoaderInterface
    {
        // Get routes file path
        $filepath = $this->parameterBag->get('reaccion_cms_routes.file_path');

        // Create file if not exists
        $this->createIfFileNotExist($filepath);

        // Get data
        try {
            $this->routes = json_decode(file_get_contents($filepath), true);
        } catch(\Exception $e) {
            throw new CannotLoadRoutesException($e);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param string $filePath
     */
    private function createIfFileNotExist(string $filePath) : void
    {
        if(false === file_exists($filePath)) {
            file_put_contents($filePath, '{}');
        }
    }
}
