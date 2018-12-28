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
     * @return  String   $title     Site name
     */
    public function paginate($total = 0, $page = null) : Array
    {
        $pagination = [];
        $limit  = 1; // TODO: inject parameter in current method
        $page   = ($page == null) ? 1 : $page;

        $totalPages = $total / $limit;
        $totalPages = round($totalPages, 0, PHP_ROUND_HALF_UP);

        // previous page button
        if($page <= $totalPages && $page > 1)
        {
            $pagination[0] = [
                'label' => 'Previous',
                'page' => ($page - 1),
                'active' => false
            ];
        }

        // pages numbers
        for($i = 0; $i < $totalPages; $i++)
        {
            $currPage = ($i + 1);
            $pagination[$currPage] = [
                'label' => $currPage,
                'page' => $currPage,
                'active' => ($currPage == $page) ? true : false
            ];
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

        

        var_dump($pagination);

        return $pagination;
    }

	public function getName()
    {
        return 'PaginationHelper';
    }
}