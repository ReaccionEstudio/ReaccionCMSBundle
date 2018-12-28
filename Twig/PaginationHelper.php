<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\Twig;

use Services\Managers\ManagerPermissions;
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
    public function __construct(ConfigService $config)
    {
        $this->config = $config;
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
     * @return  Array   $pagination     Pagination data
     */
    public function paginate($total = 0, $page = null) : Array
    {
        $pagination = [];
        $limit  = 1; // TODO: inject parameter in current method
        $page   = ($page == null) ? 1 : $page;

        $totalPages = $total / $limit;
        $totalPages = round($totalPages, 0, PHP_ROUND_HALF_UP);

        // previous page button
        if($page > 1)
        {
            $pagination[0] = [
                'label' => 'Previous',
                'page' => ($page - 1),
                'active' => false
            ];
        }

        $middle = $totalPages / 2;
        $middle = round($middle, 0, PHP_ROUND_HALF_UP);

        if($page <= $middle)
        {
            if($page <= 3) 
            {
                $init = 1;
                $end = 3;
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
            $init = $page - 1;
            $end = ($page < $totalPages) ? $page + 1 : $totalPages;

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
                'label' => 'Next',
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