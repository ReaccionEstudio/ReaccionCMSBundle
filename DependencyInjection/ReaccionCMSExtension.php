<?php

namespace App\ReaccionEstudio\ReaccionCMSBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class ReaccionCMSExtension extends Extension
{
	/**
     * Build the extension services
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
	public function load(array $configs, ContainerBuilder $container)
	{
		$processor = new Processor();

		$loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('reaccion_cms.xml');
	}
}