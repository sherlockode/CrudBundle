<?php

namespace Sherlockode\CrudBundle\Twig;

use Sherlockode\CrudBundle\Routing\Utils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ActionExtension extends AbstractExtension
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sherlockode_crud_path_generator', [$this, 'generatePath']),
        ];
    }

    /**
     * @param Request $request
     * @param string  $action
     * @param array   $parameters
     *
     * @return string
     */
    public function generatePath(Request $request, string $action, array $parameters): string
    {
        return $this->router->generate(
            Utils::generatePathName($request->attributes->get('_route'), $action),
            $parameters
        );
    }
}
