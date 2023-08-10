<?php

namespace Sherlockode\CrudBundle\Grid;

use Sherlockode\CrudBundle\Filter\FilterRegistry;

class Grid
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @var array
     */
    private array $actionTemplates;

    /**
     * @var int
     */
    private int $pageSize;

    /**
     * @var array
     */
    private array $fieldTemplates;

    /**
     * @var array
     */
    private array $sorting = [];

    /**
     * @var array
     */
    private array $filters = [];

    /**
     * @var Field[]
     */
    private array $fields = [];

    /**
     * @var Action[]
     */
    private array $actions = [];

    /**
     * @var array
     */
    private array $filterTemplates;

    /**
     * @var bool
     */
    private bool $deleteConfirmation;

    /**
     * @param FilterRegistry $filterRegistry
     * @param array          $config
     * @param array          $actionTemplates
     * @param array          $fieldTemplates
     * @param array          $filterTemplates
     *
     * @throws \ReflectionException
     */
    public function __construct(FilterRegistry $filterRegistry, array $config = [], array $actionTemplates = [], array $fieldTemplates = [], array $filterTemplates = [])
    {
        $this->config = $config;
        $this->actionTemplates = $actionTemplates;
        $this->fieldTemplates = $fieldTemplates;
        $this->filterTemplates = $filterTemplates;
        $this->pageSize = $this->config['grid']['settings']['page_size'] ?? 20;
        $this->sorting = $this->config['grid']['sorting'] ?? [];
        $this->deleteConfirmation = $this->config['config']['delete_confirmation'] ?? true;

        $className = strtolower((new \ReflectionClass($this->config['config']['class']))->getShortName());

        $this->generateFilters($filterRegistry, $this->config['grid']['filters'] ?? []);
        $this->generateFields($this->config['grid']['fields'] ?? [], $className);
        $this->generateActions($this->config['grid']['actions'] ?? []);
    }

    /**
     * @param array|mixed $sorting
     *
     * @return $this
     */
    public function setSorting(array $sorting): self
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * @return array
     */
    public function getSorting(): array
    {
        return $this->sorting;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return (int) $this->pageSize;
    }

    /**
     * @param string $name
     *
     * @return Filter|null
     */
    public function getFilter(string $name): ?Filter
    {
        return $this->filters[$name] ?? null;
    }

    /**
     * @return bool
     */
    public function hasDeleteConfirmation(): bool
    {
        return $this->deleteConfirmation;
    }

    /**
     * @param bool $deleteConfirmation
     *
     * @return $this
     */
    public function setHasDeleteConfirmation(bool $deleteConfirmation): self
    {
        $this->deleteConfirmation = $deleteConfirmation;

        return $this;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    private function camelCaseToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    /**
     * @param FilterRegistry $filterRegistry
     * @param array          $filters
     *
     * @return void
     */
    private function generateFilters(FilterRegistry $filterRegistry, array $filters): void
    {
        foreach ($filters as $key => $data) {
            $filter = new Filter();
            $filter->setName($key);
            $filter->setLabel($data['label'] ?? 'sherlockode_crud.filter.' . $this->camelCaseToSnakeCase($key));
            $filter->setType($data['type']);
            $filter->setFilterType($filterRegistry->get($data['type'])->getFormType());
            $filter->setTemplate($this->filterTemplates[$data['type']]);
            $filter->setOptions(array_merge($data['options'], ['label' => 'sherlockode_crud.filter.'.$key]));

            $this->filters[$key] = $filter;
        }
    }

    /**
     * @param array  $fields
     * @param string $className
     *
     * @return void
     */
    private function generateFields(array $fields, string $className): void
    {
        foreach ($fields as $key => $data) {
            $field = new Field();
            $field->setKey($key);
            $field->setLabel($data['label'] ?? 'sherlockode_crud.' . $className . '.' . $this->camelCaseToSnakeCase($key));
            $field->setOptions($data['options'] ?? []);
            $field->setSortable(array_key_exists('sortable', $data));
            $field->setPath($data['path'] ?? null);

            if (isset($data['type']) && isset($this->fieldTemplates[$data['type']])) {
                $field->setTemplate($this->fieldTemplates[$data['type']]);
            }

            $this->fields[$key] = $field;
        }
    }

    /**
     * @param array $actions
     *
     * @return void
     */
    private function generateActions(array $actions): void
    {
        foreach ($actions as $key => $data) {
            if (isset($this->actionTemplates[$key])) {
                $action = new Action();
                $action->setTemplate($this->actionTemplates[$key]);

                $this->actions[$key] = $action;
            }
        }
    }
}
