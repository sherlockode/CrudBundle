<?php

namespace Sherlockode\CrudBundle\Grid;

class GridConfiguration
{
    private $configs;

    /**
     * @param array $configs
     */
    public function __construct(?array $configs = [])
    {
        $this->configs = $configs;
    }

    /**
     * @param string $code
     *
     * @return array
     * @throws \Exception
     */
    public function getConfigurationByCode(string $code)
    {
        if (!isset($this->configs[$code])) {
            throw new \Exception(sprintf('No grid configuration found for code "%s"', $code));
        }

        return $this->configs[$code];
    }
}
