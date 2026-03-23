<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\DependencyInjection\Compiler;

use Sherlockode\CrudBundle\Filter\FilterRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FilterPass implements CompilerPassInterface
{
    /**
     * @return void
     */
    public function process(ContainerBuilder $container): void
    {
        $filterRegistry = $container->findDefinition(FilterRegistry::class);

        foreach (array_keys($container->findTaggedServiceIds('sherlockode_crud.filter')) as $id) {
            $filterRegistry->addMethodCall('addFilter', [new Reference($id)]);
        }
    }
}
