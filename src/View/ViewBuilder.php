<?php

namespace Sherlockode\CrudBundle\View;

class ViewBuilder
{
    /**
     * @var ViewConfiguration
     */
    private $viewConfiguration;

    /**
     * @var array
     */
    private $fieldTemplates;

    /**
     * @param ViewConfiguration $viewConfiguration
     * @param array             $fieldTemplates
     */
    public function __construct(ViewConfiguration $viewConfiguration, array $fieldTemplates = [])
    {
        $this->viewConfiguration = $viewConfiguration;
        $this->fieldTemplates = $fieldTemplates;
    }

    /**
     * @param string $code
     *
     * @return View
     */
    public function build(string $code): View
    {
        $config = $this->viewConfiguration->getConfigurationByCode($code);

        return new View($config, $this->fieldTemplates);
    }
}
