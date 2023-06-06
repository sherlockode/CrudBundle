<?php

namespace Sherlockode\CrudBundle\View;

class ViewConfiguration
{
    /**
     * @var array
     */
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
            throw new \Exception(sprintf('No view configuration found for code "%s"', $code));
        }

        return $this->configs[$code];
    }
}
