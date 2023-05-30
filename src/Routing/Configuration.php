<?php

namespace Sherlockode\CrudBundle\Routing;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('routing');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('redirect_after_create')->defaultValue('update')->end()
                ->scalarNode('redirect_after_update')->defaultValue('update')->end()
                ->scalarNode('base_name')->cannotBeEmpty()->end()
                ->scalarNode('resource_name')->cannotBeEmpty()->end()
                ->scalarNode('permission')->defaultValue(false)->end()
                ->scalarNode('templates')->cannotBeEmpty()->end()
                ->variableNode('vars')->end()
                ->arrayNode('except')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('only')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
