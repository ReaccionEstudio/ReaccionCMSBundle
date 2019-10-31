<?php

namespace ReaccionEstudio\ReaccionCMSBundle\Services\AppContext;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Menu\MenuService AS MenuUtils;

/**
 * Class AppContextService.
 */
class AppContextService
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var MenuUtils
     */
    private $menuUtils;

    /**
     * @var array
     */
    private $appContext = [];

    /**
     * AppContextService constructor.
     *
     * @param RequestStack $requestStack
     * @param ContainerInterface $container
     */
    public function __construct(
        RequestStack $requestStack,
        ParameterBagInterface $pagameterBag,
        MenuUtils $menuUtils
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->environment = $pagameterBag->get('kernel.environment');
        $this->menuUtils = $menuUtils;
        $this->configureOptions();
    }

    /**
     * Añade datos al array de $appContext.
     *
     * @param string $key
     * @param $value
     *
     * @return AppContextService
     */
    public function add(string $key, $value): self
    {
        $this->appContext[$key] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAppContext(): array
    {
        return $this->appContext;
    }

    /**
     * Default options.
     */
    private function configureOptions()
    {
        $this->appContext = [
            'app' => [
                'environment' => $this->environment,
                'language' => 'TODO',
            ],
            'request' => $this->getRequestInfo(),
        ];
    }

    /**
     * Obtiene la información del enrutador.
     *
     * @return array
     */
    private function getRequestInfo(): array
    {
        $requestInfo = [];
        $requestAttr = ($this->request) ? $this->request->attributes : null;

        if (!empty($requestAttr)) {
            $route = ($requestAttr->get('_route')) ?? null;

            $requestInfo = [
                'route' => $route,
                'route_slug' => $this->menuUtils->getActiveRoute(),
                'params' => [
                    'route' => ($requestAttr->get('_route_params')) ?? null,
                ],
            ];
        }

        return $requestInfo;
    }
}
