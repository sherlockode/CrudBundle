<?php

namespace Sherlockode\CrudBundle;

use Sherlockode\CrudBundle\DependencyInjection\Compiler\FilterPass;
use Sherlockode\CrudBundle\Filter\FilterInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SherlockodeCrudBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FilterPass());
        $container->registerForAutoconfiguration(FilterInterface::class)->addTag('sherlockode_crud.filter');
    }
}
