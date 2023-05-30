<?php

namespace Sherlockode\CrudBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sherlockode_crud');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $this->addGridsSection($rootNode);
        $this->addTemplatesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     *
     * @return void
     */
    private function addTemplatesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('action')
                            ->useAttributeAsKey('code')
                            ->scalarPrototype()->end()
                        ->end()
                        ->arrayNode('field')
                            ->useAttributeAsKey('code')
                            ->scalarPrototype()->end()
                        ->end()
                        ->arrayNode('filter')
                            ->useAttributeAsKey('code')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     *
     * @return void
     */
    private function addGridsSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('crud')
                    ->useAttributeAsKey('code')
                    ->arrayPrototype()
                    ->children()
                        ->arrayNode('config')
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->end()
                                ->scalarNode('form')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                        ->arrayNode('grid')
                            ->children()
                                ->arrayNode('repository')
                                    ->children()
                                        ->scalarNode('method')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('sorting')
                                    ->performNoDeepMerging()
                                    ->useAttributeAsKey('name')
                                    ->enumPrototype()->values(['asc', 'desc'])->cannotBeEmpty()->end()
                                ->end()
                                ->arrayNode('filters')
                                ->useAttributeAsKey('name')
                                    ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('type')->cannotBeEmpty()->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('fields')
                                ->useAttributeAsKey('name')
                                    ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('label')->cannotBeEmpty()->end()
                                            ->scalarNode('type')->end()
                                            ->scalarNode('sortable')->end()
                                            ->arrayNode('options')
                                                ->performNoDeepMerging()
                                                ->variablePrototype()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('actions')
                                    ->useAttributeAsKey('name')
                                    ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('template')->cannotBeEmpty()->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('settings')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->variableNode('page_size')->defaultValue(20)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
