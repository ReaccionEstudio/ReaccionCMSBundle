<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Router;

use Doctrine\ORM\EntityManagerInterface;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Model\RoutesCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Constants\FileLoaderConstants;

/**
 * Class RouterSchemaUpdater
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Router
 */
class RouterSchemaUpdater
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * RouterSchemaUpdater constructor.
     * @param RoutesCollection $routes
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag)
    {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
        $this->filepath = $parameterBag->get(FileLoaderConstants::ROUTES_FILE_PATH_CONFIG_PARAM_NAME);
    }

    /**
     * @return bool
     */
    public function update() : bool
    {
        $pages = $this->em->getRepository(Page::class)->findBy(['enabled' => true]);

        if(empty($pages)) {
            $this->setFileData();
        }

        $routesArray = [];

        /**
         * Schema example:
         *
         *   {
         *       "name" : "Test",
         *       "slug" : "test-one",
         *       "main_page" : "1",
         *       "language" : "es",
         *       "template" : "page.html.twig",
         *       "type" : "Page",
         *       "id" : "1"
         *   }
         *
         */
        foreach($pages as $page) {
            /** @var Page $page */
            $routesArray[] = [
                'name' => $page->getName(),
                'slug' => $page->getSlug(),
                'main_page' => $page->isMainPage(),
                'language' => $page->getLanguage(),
                'template' => $page->getTemplateView(),
                'type' => $page->getType(),
                'id' => $page->getId()
            ];
        }

        $json = json_encode($routesArray);
        return $this->setFileData($json);
    }

    /**
     * @param string $json
     */
    public function setFileData(string $json = '{}')
    {
        try{
            file_put_contents($this->filepath, $json);
            return true;
        }catch(\Exception $e){
            return false;
        }
    }
}
