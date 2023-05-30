<?php

namespace Sherlockode\CrudBundle\DependencyInjection\Compiler;

use Sherlockode\CrudBundle\Filter\FilterRegistry;
use Sherlockode\CrudBundle\Form\Type\FormTypeRegistry;
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
        $filterTypeRegistry = $container->findDefinition(FormTypeRegistry::class);

        foreach ($container->findTaggedServiceIds('sherlockode_crud.filter') as $id => $tags) {
            $filterRegistry->addMethodCall('addFilter', [new Reference($id)]);
            $filterTypeRegistry->addMethodCall('add', [$tags[0]['type'], $tags[0]['form_type']]);
        }
    }
}
