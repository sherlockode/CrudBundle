<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Filter;

class FilterRegistry
{
    /**
     * @var FilterInterface[]
     */
    private array $filters = [];

    public function __construct()
    {
    }

    public function get(string $type): ?FilterInterface
    {
        foreach ($this->filters as $filter) {
            if ($filter->supports($type)) {
                return $filter;
            }
        }

        return null;
    }

    /**
     * @return $this
     */
    public function addFilter(FilterInterface $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }
}
