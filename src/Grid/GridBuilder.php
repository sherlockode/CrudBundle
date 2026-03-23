<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Grid;

use Sherlockode\CrudBundle\Filter\FilterRegistry;

class GridBuilder
{
    /**
     * @var mixed[]
     */
    public $filterTemplates;
    public function __construct(
        private readonly GridConfiguration $gridConfiguration,
        private readonly FilterRegistry $filterRegistry,
        private readonly array $actionTemplates = [],
        private readonly array $fieldTemplates = [],
        array $filterTemplates = []
    ) {
        $this->filterTemplates = $filterTemplates;
    }

    public function build(string $code): Grid
    {
        $config = $this->gridConfiguration->getConfigurationByCode($code);

        return new Grid($this->filterRegistry, $config, $this->actionTemplates, $this->fieldTemplates, $this->filterTemplates);
    }
}
