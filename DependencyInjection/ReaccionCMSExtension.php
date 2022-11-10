<?php

namespace ReaccionEstudio\ReaccionCMSBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

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
		$loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('user.xml');
        $loader->load('pages.xml');
        $loader->load('entries.xml');
        $loader->load('services.xml');
        $loader->load('comments.xml');
        $loader->load('languages.xml');
        $loader->load('event_listeners.xml');
        $loader->load('twig_extensions.xml');

        $fosUserConfig = Yaml::parse(
            file_get_contents(__DIR__.'/../config/packages/fos_user.yaml')
        );
//        $processor->processConfiguration($fosUserConfig);
        $configs = array_merge($fosUserConfig, $configs);

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        foreach ($config as $key => $value) {
            $container->setParameter('reaccion_cms.' . $key, $value);
        }
	}
}
