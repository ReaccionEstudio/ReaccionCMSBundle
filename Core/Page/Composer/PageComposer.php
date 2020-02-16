<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Page\Composer;

use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Collections\PageContentCollection;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Page;
use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Seo;
use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Exceptions\NotFoundRouteDataException;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page as PageEntity;

/**
 * Class PageComposer
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Page\Composer
 */
class PageComposer
{
    /**
     * @var PageEntity $page
     */
    private $pageData;

    /**
     * PageComposer constructor.
     * @param PageEntity $pageData
     */
    public function __construct(PageEntity $pageData)
    {
        $this->pageData = $pageData;
    }

    /**
     * @return Page
     * @throws NotFoundRouteDataException
     */
    public function compose() : Page
    {
        if (null === $this->pageData) {
            throw new NotFoundRouteDataException();
        }

        // Page Content
        $pageContent = new PageContentCollection($this->pageData->getContent()->toArray());

        // Seo
        $seo = new Seo();
        $seo->setTitle($this->pageData->getSeoTitle());
        $seo->setDescription($this->pageData->getSeoDescription());
        $seo->setKeywords($this->pageData->getSeoKeywords());

        // Page
        $page = new Page();
        $page->setName($this->pageData->getName());
        $page->setSlug($this->pageData->getSlug());
        $page->setLanguage($this->pageData->getLanguage());
        $page->setTemplate($this->pageData->getTemplateView());
        $page->setContent($pageContent);
        $page->setSeo($seo);

        return $page;
    }
}
