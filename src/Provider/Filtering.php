<?php

namespace Sherlockode\CrudBundle\Provider;


use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Filter\FilterRegistry;
use Sherlockode\CrudBundle\Grid\Grid;

class Filtering
{
    /**
     * @var FilterRegistry
     */
    private $filterRegistry;

    /**
     * @param FilterRegistry $filterRegistry
     */
    public function __construct(FilterRegistry $filterRegistry)
    {
        $this->filterRegistry = $filterRegistry;
    }

    /**
     * @param QueryBuilder $builder
     * @param Grid         $grid
     * @param array        $criteria
     *
     * @return void
     *
     * @throws \Exception
     */
    public function apply(QueryBuilder $builder, Grid $grid, array $criteria): void
    {
        foreach ($criteria as $key => $data) {
            $gridFilter = $grid->getFilter($key);

            if (null === $gridFilter) {
                continue;
            }

            $filter = $this->filterRegistry->get($gridFilter->getType());
            if (null === $filter) {
                throw new \Exception(sprintf('Fitler type %s does not exist', $gridFilter->getFilterType()));
            }

            $filter->apply($builder, $key, $data);
        }
    }
}
