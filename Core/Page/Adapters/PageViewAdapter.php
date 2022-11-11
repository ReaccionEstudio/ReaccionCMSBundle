<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Page\Adapters;

use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Page;
use ReaccionEstudio\ReaccionCMSBundle\Core\View\ViewAdapter;
use ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;

/**
 * Class PageViewAdapter
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Page\Adapters
 */
class PageViewAdapter implements ViewAdapter
{
    /**
     * @var Page $page
     */
    private $page;

    /**
     * PageViewAdapter constructor.
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $arrayContent = [];

        // TODO: create PageContent model
        foreach ($this->page->getContent() as $item) {
            /** @var PageContent $item */
            if (true === $item->isEnabled()) {
                $arrayContent[$item->getName()] = [
                    'value' => $item->getValue(),
                    'position' => $item->getPosition(),
                    'type' => $item->getType(),
                    'options' => $item->getOptions(),
                    'createdAt' => $item->getCreatedAt()
                ];
            }
        }

        return [
            'name' => $this->page->getName(),
            'content' => $arrayContent,
            'seo' => [
                'title' => $this->page->getSeo()->getTitle(),
                'description' => $this->page->getSeo()->getDescription(),
                'keywords' => $this->page->getSeo()->getKeywords()
            ]
            // ...
        ];
    }
}
