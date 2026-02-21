<?php

namespace MartenaSoft\SiteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('site');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('sites')
                    ->useAttributeAsKey('name')
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('id')->end()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('host')->end()
                                    ->scalarNode('preview_on_main_limit')->end()
                                    ->scalarNode('status')->end()
                                    ->arrayNode('active_by_ips')
                                ->scalarPrototype()->end()
                                ->end()
                                    ->scalarNode('default_language')->end()
                                    ->scalarNode('template_path')->end()
                                    ->arrayNode('languages')
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
