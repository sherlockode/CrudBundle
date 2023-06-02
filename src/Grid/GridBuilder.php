<?php

namespace Sherlockode\CrudBundle\Grid;

use Sherlockode\CrudBundle\Filter\FilterRegistry;

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
     * @var FilterRegistry
     */
    private $filterRegistry;

    /**
     * @param GridConfiguration $gridConfiguration
     * @param FilterRegistry    $filterRegistry
     * @param array             $actionTemplates
     * @param array             $fieldTemplates
     * @param array             $filterTemplates
     */
    public function __construct(GridConfiguration $gridConfiguration, FilterRegistry $filterRegistry, array $actionTemplates = [], array $fieldTemplates = [], array $filterTemplates = [])
    {
        $this->gridConfiguration = $gridConfiguration;
        $this->filterRegistry = $filterRegistry;
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

        return new Grid($this->filterRegistry, $config, $this->actionTemplates, $this->fieldTemplates, $this->filterTemplates);
    }
}
