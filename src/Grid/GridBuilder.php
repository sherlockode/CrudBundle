<?php

namespace Sherlockode\CrudBundle\Grid;

class GridBuilder
{
    private $gridConfiguration;

    /**
     * @var array
     */
    private $actionTemplates;

    /**
     * @var array
     */
    private $fieldTemplates;

    /**
     * @param GridConfiguration $gridConfiguration
     * @param array             $actionTemplates
     * @param array             $fieldTemplates
     * @param array             $filterTemplates
     */
    public function __construct(GridConfiguration $gridConfiguration, array $actionTemplates = [], array $fieldTemplates = [], array $filterTemplates = [])
    {
        $this->gridConfiguration = $gridConfiguration;
        $this->actionTemplates = $actionTemplates;
        $this->fieldTemplates = $fieldTemplates;
        $this->filterTemplates = $filterTemplates;
    }

    /**
     * @param string $code
     *
     * @return Grid
     */
    public function build(string $code): Grid
    {
        $config = $this->gridConfiguration->getConfigurationByCode($code);

        return new Grid($config, $this->actionTemplates, $this->fieldTemplates, $this->filterTemplates);
    }
}
