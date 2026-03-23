<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Twig;

use Twig\Attribute\AsTwigFunction;
use Sherlockode\CrudBundle\Routing\Utils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class ActionExtension
{
    public function __construct(private readonly Router $router)
    {
    }

    #[AsTwigFunction(name: 'sherlockode_crud_path_generator')]
    public function generatePath(Request $request, string $action, array $parameters = []): string
    {
        return $this->router->generate(
            Utils::generatePathName($request->attributes->get('_route'), $action),
            $parameters
        );
    }
}
