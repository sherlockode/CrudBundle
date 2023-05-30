<?php

namespace Sherlockode\CrudBundle\DependencyInjection;

use Sherlockode\CrudBundle\Controller\ResourceController;
use Sherlockode\CrudBundle\Grid\GridBuilder;
use Sherlockode\CrudBundle\Provider\DataProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class SherlockodeCrudExtension extends Extension
{
    /**
     * @var string[]
     */
    private $defaultFieldType = ['date', 'boolean'];

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.xml');

        $configuration = new Configuration();

        $grids = [];
        $config = $this->processConfiguration($configuration, $configs);
        $config = $this->defaultTemplate($config);

        $this->checkFieldTypeExist($config);

        $container->setParameter('sherlockode_crud.grid.templates.action', $config['templates']['action']);
        $container->setParameter('sherlockode_crud.grid.templates.field', $config['templates']['field']);
        $container->setParameter('sherlockode_crud.grid.templates.filter', $config['templates']['filter']);

        foreach ($config['crud'] as $key => $crud) {
            $definition = new Definition(ResourceController::class);
            $definition
                ->setPublic(true)
                ->setArguments([
                    new Reference(GridBuilder::class),
                    new Reference(DataProvider::class),
                    new Reference('doctrine.orm.entity_manager'),
                    $key,
                    $crud['config']['class'],
                    $crud['config']['form'],
                ])
                ->addMethodCall('setContainer', [new Reference('service_container')])
                ->addTag('controller.service_arguments')
            ;

            $grids[$key] = $crud;
            $container->setDefinition(sprintf('%s.%s', 'sherlockode_crud.controller', $key), $definition);
        }

        $container->setParameter('sherlockode_crud.grids_definitions', $grids);
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function defaultTemplate(array $config): array
    {
        if (!isset($config['templates']['action']['update'])) {
            $config['templates']['action']['update'] = '@SherlockodeCrud/common/grid/action/update.html.twig';
        }

        if (!isset($config['templates']['action']['delete'])) {
            $config['templates']['action']['delete'] = '@SherlockodeCrud/common/grid/action/delete.html.twig';
        }

        if (!isset($config['templates']['field']['date'])) {
            $config['templates']['field']['date'] = '@SherlockodeCrud/common/grid/field/date.html.twig';
        }

        if (!isset($config['templates']['field']['boolean'])) {
            $config['templates']['field']['boolean'] = '@SherlockodeCrud/common/grid/field/boolean.html.twig';
        }

        if (!isset($config['templates']['filter']['string'])) {
            $config['templates']['filter']['string'] = '@SherlockodeCrud/common/grid/filter/string.html.twig';
        }

        if (!isset($config['templates']['filter']['boolean'])) {
            $config['templates']['filter']['boolean'] = '@SherlockodeCrud/common/grid/filter/boolean.html.twig';
        }

        if (!isset($config['templates']['filter']['date'])) {
            $config['templates']['filter']['date'] = '@SherlockodeCrud/common/grid/filter/date.html.twig';
        }

        if (!isset($config['templates']['filter']['date_range'])) {
            $config['templates']['filter']['date_range'] = '@SherlockodeCrud/common/grid/filter/date_range.html.twig';
        }

        return $config;
    }

    /**
     * @param array $config
     *
     * @return void
     */
    private function checkFieldTypeExist(array $config): void
    {
        $fieldTypesDefined = array_diff(array_keys($config['templates']['field']) ?? [], $this->defaultFieldType);
        $fieldTypesUsed = [];

        foreach ($config['crud'] as $item) {
            foreach ($item['grid']['fields'] as $field) {
                if (isset($field['type'])) {
                    $fieldTypesUsed[] = $field['type'];
                }
            }
        }

        $fieldTypesUsed = array_diff($fieldTypesUsed, $this->defaultFieldType);
        $fieldTypesDefinedNotUsed = array_diff($fieldTypesDefined, $fieldTypesUsed);
        $fieldTypesUsedNotDefined = array_diff($fieldTypesUsed, $fieldTypesDefined);

        if (!empty($fieldTypesDefinedNotUsed)) {
            throw new \InvalidArgumentException(
                sprintf('You have defined field type(s) but you not use it / them : %s', implode(', ', $fieldTypesDefinedNotUsed))
            );
        }

        if (!empty($fieldTypesUsedNotDefined)) {
            throw new \InvalidArgumentException(
                sprintf('You use field type(s) but you have not defined it / them : %s', implode(', ', $fieldTypesUsedNotDefined))
            );
        }
    }
}
