<?php

namespace Sherlockode\CrudBundle\Routing;

class Utils
{
    /**
     * @param string $route
     * @param string $actionName
     *
     * @return string
     */
    public static function generatePathName(string $route, string $actionName): string
    {
        $route = explode('_', $route);
        $route[count($route) - 1] = $actionName;

        return implode('_', $route);
    }
}
