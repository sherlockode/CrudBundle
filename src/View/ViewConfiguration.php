<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\View;

class ViewConfiguration
{
    /**
     * @param array $configs
     */
    public function __construct(private ?array $configs = [])
    {
    }

    /**
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
