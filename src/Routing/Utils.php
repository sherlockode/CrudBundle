<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Routing;

class Utils
{
    public static function generatePathName(string $route, string $actionName): string
    {
        $route = explode('_', $route);
        $route[count($route) - 1] = $actionName;

        return implode('_', $route);
    }
}
