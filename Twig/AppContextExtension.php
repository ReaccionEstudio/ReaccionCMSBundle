<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Twig;

use ReaccionEstudio\ReaccionCMSBundle\Services\AppContext\AppContextService;

class AppContextExtension extends \Twig_Extension
{
    /**
     * AppContextExtension constructor.
     *
     * @param AppContextService $appContextService
     */
    public function __construct(AppContextService $appContextService)
    {
        $this->appContextService = $appContextService;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('printAppContext', [$this, 'printAppContext']),
        ];
    }

    /**
     * Imprime un JSON con el contexto de la app.
     *
     * @return false|string
     */
    public function printAppContext()
    {
        return json_encode($this->appContextService->getAppContext());
    }
}
