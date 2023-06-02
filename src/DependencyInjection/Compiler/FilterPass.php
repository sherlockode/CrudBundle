<?php

namespace Sherlockode\CrudBundle\DependencyInjection\Compiler;

use Sherlockode\CrudBundle\Filter\FilterRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FilterPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $filterRegistry = $container->findDefinition(FilterRegistry::class);

        foreach ($container->findTaggedServiceIds('sherlockode_crud.filter') as $id => $tags) {
            $filterRegistry->addMethodCall('addFilter', [new Reference($id)]);
        }
    }
}
