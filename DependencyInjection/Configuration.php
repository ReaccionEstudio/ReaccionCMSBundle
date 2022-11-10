<?php

namespace ReaccionEstudio\ReaccionCMSBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('reaccion_cms');

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('encryption_key')->end()
            ->scalarNode('locale')->end()
        ;

        return $treeBuilder;
    }
}
