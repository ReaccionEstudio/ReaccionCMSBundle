<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

use Services\Managers\ManagerPermissions;
use Symfony\Component\Translation\TranslatorInterface;
use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;

/**
 * PaginationHelper class (Twig_Extension)
 *
 * @author Alberto Vian <alberto@reaccionestudio.com>
 */
class PaginationHelper extends \Twig_Extension
{
    /**
     * Constructor
     *
     * @param ConfigService     $config     Configuration service
     */
    public function __construct(ConfigService $config, TranslatorInterface $translator)
    {
        $this->config       = $config;
        $this->translator   = $translator;
    }

	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('paginate', array($this, 'paginate'))
        );
    }

    /**
     * Get pagination data
     *
     * @param   Integer     $total          Total comments
     * @param   Integer     $page           Current page
     * @return  Array       $pagination     Pagination data
     */
    public function paginate(Int $total = 0, $page = null) : Array
    {
        $pagination = [];
        $page   = ($page == null) ? 1 : $page;
        $limit  = $this->config->get("entries_comments_pagination_limit");

        $totalPages = $total / $limit;
        $totalPages = ceil($totalPages);

        if($totalPages < 2) return [];

        // previous page button
        if($page > 1)
        {
            $pagination[0] = [
                'label' => $this->translator->trans('pagination.previous'),
                'page' => ($page - 1),
                'active' => false
            ];
        }

        $middle = $totalPages / 2;
        $middle = ceil($middle);

        if($page <= $middle)
        {
            if($page <= 3) 
            {
                $init = 1;
                $end  = ($totalPages < 3) ? $totalPages : 3;
            }
            else if($page > 3)
            {
                $init = ($page - 1);
                $end = $page + 1;
            }

            if($page > 3)
            {
                // add dots separator and first page
                $pagination[] = [
                    'label' => 1,
                    'page' => 1,
                    'active' => false
                ];

                $pagination[] = [
                    'label' => '...',
                    'page' => null,
                    'active' => false
                ];
            }

            for($i = $init; $i <= $end; $i++)
            {
                $pagination[] = [
                    'label' => $i,
                    'page' => $i,
                    'active' => ($i == $page) ? true : false
                ];
            }
        }
        else if($page > $middle)
        {
            // add first page number
            $pagination[] = [
                'label' => 1,
                'page' => 1,
                'active' => false
            ];
        }

        // dots separator
        if($totalPages > 3)
        {
            $pagination[] = [
                'label' => '...',
                'page' => null,
                'active' => false
            ];
        }

        if($page <= $middle && $totalPages > 3)
        {
            // add last page
            $pagination[] = [
                'label' => $totalPages,
                'page' => $totalPages,
                'active' => false
            ];
        }
        else if($page > $middle)
        {
            // numbers on right
            $init = ($totalPages > 3) ? ($page - 1) : $page;
            $end  = ($page < $totalPages) ? $page + 1 : $totalPages;


            for($i = $init; $i <= $end; $i++)
            {
                $pagination[] = [
                    'label' => $i,
                    'page' => $i,
                    'active' => ($i == $page) ? true : false
                ];
            }

            if($init < ($totalPages - 2) && $totalPages > 3)
            {
                // add dots separator and last page
                $pagination[] = [
                    'label' => '...',
                    'page' => null,
                    'active' => false
                ];

                $pagination[] = [
                    'label' => $totalPages,
                    'page' => $totalPages,
                    'active' => false
                ];
            }
        }

        // next page button
        if($page < $totalPages)
        {
            $pagination[] = [
                'label' => $this->translator->trans('pagination.next'),
                'page' => ($page + 1),
                'active' => false
            ];
        }

        return $pagination;
    }

	public function getName()
    {
        return 'PaginationHelper';
    }
}