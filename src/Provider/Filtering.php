<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Provider;


use Sherlockode\CrudBundle\Grid\Filter;
use Sherlockode\CrudBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Filter\FilterRegistry;
use Sherlockode\CrudBundle\Grid\Grid;

class Filtering
{
    public function __construct(private readonly FilterRegistry $filterRegistry)
    {
    }

    /**
     *
     *
     * @throws \Exception
     */
    public function apply(QueryBuilder $builder, Grid $grid, array $criteria): void
    {
        foreach ($criteria as $key => $data) {
            $gridFilter = $grid->getFilter($key);

            if (!$gridFilter instanceof Filter) {
                continue;
            }

            $filter = $this->filterRegistry->get($gridFilter->getType());
            if (!$filter instanceof FilterInterface) {
                throw new \Exception(sprintf('Fitler type %s does not exist', $gridFilter->getFilterType()));
            }

            $filter->apply($builder, $key, $data);
        }
    }
}
