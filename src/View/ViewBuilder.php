<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\View;

class ViewBuilder
{
    public function __construct(
        private readonly ViewConfiguration $viewConfiguration,
        private readonly array $fieldTemplates = []
    ) {
    }

    public function build(string $code): View
    {
        $config = $this->viewConfiguration->getConfigurationByCode($code);

        return new View($config, $this->fieldTemplates);
    }
}
