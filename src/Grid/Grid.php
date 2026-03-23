<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Grid;

use Sherlockode\CrudBundle\Filter\FilterRegistry;

class Grid
{
    private readonly int $pageSize;

    private array $sorting = [];

    private array $filters = [];

    /**
     * @var Field[]
     */
    private array $fields = [];

    /**
     * @var Action[]
     */
    private array $actions = [];

    private bool $deleteConfirmation;

    /**
     *
     * @throws \ReflectionException
     */
    public function __construct(
        FilterRegistry $filterRegistry,
        private array $config = [],
        private array $actionTemplates = [],
        private array $fieldTemplates = [],
        private array $filterTemplates = []
    ) {
        $this->pageSize = $this->config['grid']['settings']['page_size'] ?? 20;
        $this->sorting = $this->config['grid']['sorting'] ?? [];
        $this->deleteConfirmation = $this->config['config']['delete_confirmation'] ?? true;

        $this->generateFilters($filterRegistry, $this->config['grid']['filters'] ?? []);
        $this->generateFields($this->config['grid']['fields'] ?? [], $this->config['config']['crud_name']);
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

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getFilter(string $name): ?Filter
    {
        return $this->filters[$name] ?? null;
    }

    public function hasDeleteConfirmation(): bool
    {
        return $this->deleteConfirmation;
    }

    /**
     * @return $this
     */
    public function setHasDeleteConfirmation(bool $deleteConfirmation): self
    {
        $this->deleteConfirmation = $deleteConfirmation;

        return $this;
    }

    private function camelCaseToSnakeCase(string $string): string
    {
        return strtolower((string) preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    private function generateFilters(FilterRegistry $filterRegistry, array $filters): void
    {
        foreach ($filters as $key => $data) {
            $filter = new Filter();
            $filter->setName($key);
            $filter->setLabel($data['label'] ?? 'sherlockode_crud.filter.' . $this->camelCaseToSnakeCase($key));
            $filter->setType($data['type']);
            $filter->setFilterType($filterRegistry->get($data['type'])->getFormType());
            $filter->setTemplate($this->filterTemplates[$data['type']]);
            $filter->setOptions(array_merge($data['options'], ['label' => $filter->getLabel()]));

            $this->filters[$key] = $filter;
        }
    }

    private function generateFields(array $fields, string $gridName): void
    {
        foreach ($fields as $key => $data) {
            $field = new Field();
            $field->setKey($key);
            $field->setLabel($data['label'] ?? 'sherlockode_crud.' . $gridName . '.' . $this->camelCaseToSnakeCase($key));
            $field->setOptions($data['options'] ?? []);
            $field->setSortable(array_key_exists('sortable', $data));
            $field->setPath($data['path'] ?? null);

            if (isset($data['type']) && isset($this->fieldTemplates[$data['type']])) {
                $field->setTemplate($this->fieldTemplates[$data['type']]);
            }

            $this->fields[$key] = $field;
        }
    }

    private function generateActions(array $actions): void
    {
        foreach (array_keys($actions) as $key) {
            if (isset($this->actionTemplates[$key])) {
                $action = new Action();
                $action->setTemplate($this->actionTemplates[$key]);

                $this->actions[$key] = $action;
            }
        }
    }
}
