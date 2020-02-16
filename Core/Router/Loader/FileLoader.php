<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader;

use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Constants\FileLoaderConstants;
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
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string $filepath
     */
    private $filepath;

    /**
     * FileLoader constructor.
     * @param EntityManagerInterface $em
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag)
    {
        $this->em = $em;
        $this->routes = [];
        $this->filepath = $parameterBag->get(FileLoaderConstants::ROUTES_FILE_PATH_CONFIG_PARAM_NAME);
    }

    /**
     * Loads routes from a system file.
     *
     * @return LoaderInterface
     */
    public function load(): LoaderInterface
    {
        // Create file if not exists
        $this->createIfFileNotExist();

        // Get data
        try {
            $this->routes = json_decode(file_get_contents($this->filepath), true);
        } catch (\Exception $e) {
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
     * Create file if doesn't exists
     */
    private function createIfFileNotExist(): void
    {
        $filepath = $this->filepath;

        if (false === file_exists($filepath)) {
            file_put_contents($filepath, '{}');
        }
    }
}
