<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Core\Page\Adapters;

use ReaccionEstudio\ReaccionCMSBundle\Core\Page\Model\Page;
use ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;

/**
 * Class PageViewAdapter
 * @package ReaccionEstudio\ReaccionCMSBundle\Core\Page\Adapters
 */
class PageViewAdapter
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
                ];
            }
        }

        return [
            'name' => $this->page->getName(),
            'content' => $arrayContent,
            // ...
        ];
    }
}
