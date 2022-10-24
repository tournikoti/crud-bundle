<?php

namespace Tournikoti\CrudBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('tournikoti_crud');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('menus')
                    ->isRequired()
                    ->children()
                        ->arrayNode('sidebar')
                            ->isRequired()
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('label')->isRequired()->end()
                                    ->scalarNode('route')->end()
                                    ->arrayNode('arguments')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('assets')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('js')
                            ->defaultValue(['bundles/tournikoticrud/app.js'])
                            ->scalarPrototype()->end()
                        ->end()
                        ->arrayNode('css')
                            ->defaultValue(['bundles/tournikoticrud/app.css'])
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}