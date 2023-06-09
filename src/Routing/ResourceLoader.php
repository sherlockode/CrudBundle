<?php


namespace Sherlockode\CrudBundle\Routing;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

class ResourceLoader implements LoaderInterface
{
    /**
     * @param $resource
     * @param $type
     *
     * @return RouteCollection
     */
    public function load($resource, $type = null): RouteCollection
    {
        $processor = new Processor();
        $configurationDefinition = new Configuration();

        $configuration = Yaml::parse($resource);
        $configuration = $processor->processConfiguration($configurationDefinition, ['routing' => $configuration]);

        $routesToGenerate = ['index', 'create', 'update', 'show', 'delete', 'delete_confirmation'];

        $routeCollection = new RouteCollection();

        if (!empty($configuration['only'])) {
            $routesToGenerate = $configuration['only'];
        }

        if (!empty($configuration['except'])) {
            $routesToGenerate = array_diff($routesToGenerate, $configuration['except']);
        }

        if (in_array('index', $routesToGenerate, true)) {
            $routeCollection->add($this->getRouteName($configuration, 'index'), $this->createRoute($configuration, 'index'));
        }

        if (in_array('create', $routesToGenerate, true)) {
            $routeCollection->add($this->getRouteName($configuration, 'create'), $this->createRoute($configuration, 'create'));
        }

        if (in_array('update', $routesToGenerate, true)) {
            $routeCollection->add($this->getRouteName($configuration, 'update'), $this->createRoute($configuration, 'update'));
        }

        if (in_array('show', $routesToGenerate, true)) {
            $routeCollection->add($this->getRouteName($configuration, 'show'), $this->createRoute($configuration, 'show'));
        }

        if (in_array('delete', $routesToGenerate, true)) {
            $routeCollection->add($this->getRouteName($configuration, 'delete'), $this->createRoute($configuration, 'delete'));
        }

        if (in_array('delete_confirmation', $routesToGenerate, true)) {
            $routeCollection->add($this->getRouteName($configuration, 'deleteconfirmation'), $this->createRoute($configuration, 'deleteConfirmation'));
        }

        return $routeCollection;
    }

    /**
     * @param $resource
     * @param $type
     *
     * @return bool
     */
    public function supports($resource, $type = null): bool
    {
        return 'sherlockode_crud.resource' === $type;
    }

    /**
     * @return LoaderResolverInterface
     */
    public function getResolver()
    {
        // Intentionally left blank.
    }

    /**
     * @param LoaderResolverInterface $resolver
     *
     * @return void
     */
    public function setResolver(LoaderResolverInterface $resolver): void
    {
        // Intentionally left blank.
    }

    /**
     * @param array  $configuration
     * @param string $actionName
     *
     * @return Route
     */
    private function createRoute(array $configuration, string $actionName): Route
    {
        $path = '';

        switch ($actionName) {
            case 'index':
                $path = $configuration['resource_name'];
                break;
            case 'show':
                $path = sprintf('%s/{id}', $configuration['resource_name']);
                break;
            case 'create':
                $path = sprintf('%s/%s', $configuration['resource_name'], 'new');
                break;
            case 'update':
                $path = sprintf('%s/{id}/%s', $configuration['resource_name'], 'edit');
                break;
            case 'delete':
                $path = sprintf('%s/{id}/%s', $configuration['resource_name'], 'delete');
                break;
            case 'deleteConfirmation':
                $path = sprintf('%s/{id}/%s', $configuration['resource_name'], 'delete_confirmation');
                break;
        }

        $serviceId = sprintf('%s.%s.%s', $configuration['base_name'], 'controller', $configuration['resource_name']);
        $defaults = [
            '_controller' => sprintf('%s::%sAction', $serviceId, $actionName),
            '_crud' => ['vars' => [ 'page_name' => sprintf('%s.%s.%s', $configuration['base_name'], $configuration['resource_name'], $actionName)]],
        ];
        if (isset($configuration['templates']) && in_array($actionName, ['show', 'index', 'create', 'update'], true)) {
            $defaults['_crud']['template'] = sprintf('%s/%s.html.twig', $configuration['templates'], $actionName);
        }

        if (isset($configuration['redirect_after_create']) && in_array($actionName, ['create'], true)) {
            $defaults['_crud']['redirect'] = $this->getRouteName($configuration, $configuration['redirect_after_create']);
        }

        if (isset($configuration['redirect_after_update']) && in_array($actionName, ['update'], true)) {
            $defaults['_crud']['redirect'] = $this->getRouteName($configuration, $configuration['redirect_after_update']);
        }

        if (isset($configuration['vars'])) {
            $defaults['_crud']['vars'] = array_merge($defaults['_crud']['vars'], $configuration['vars']['global'] ?? [], $configuration['vars'][$actionName] ?? []);
        }

        if (isset($configuration['permission'])) {
            $defaults['_crud']['permission'] = (bool) $configuration['permission'] ?? false;
            $defaults['_crud']['resource_name'] = $configuration['resource_name'];
        }

        return new Route($path, $defaults);
    }

    /**
     * @param array  $configuration
     * @param string $action
     *
     * @return string
     */
    private function getRouteName(array $configuration, string $action): string
    {
        return sprintf('%s_%s_%s', $configuration['base_name'], $configuration['resource_name'], $action);
    }
}
