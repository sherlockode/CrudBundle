<?php

namespace Sherlockode\CrudBundle\Filter;

class FilterRegistry
{
    /**
     * @var FilterInterface[]
     */
    private $filters;

    public function __construct()
    {
        $this->filters = [];
    }

    /**
     * @param string $type
     *
     * @return FilterInterface|null
     */
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
     * @param FilterInterface $filter
     *
     * @return $this
     */
    public function addFilter(FilterInterface $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }
}
