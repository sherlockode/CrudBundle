<?php

namespace Sherlockode\CrudBundle\Provider;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Grid\Grid;

class Sorting
{
    /**
     * @param QueryBuilder $builder
     * @param Grid         $grid
     * @param array        $sorting
     *
     * @return void
     *
     * @throws \Exception
     */
    public function apply(QueryBuilder $builder, Grid $grid, array $sorting = []): void
    {
        $fields = $grid->getFields();
        $expressionBuilder = new ExpressionBuilder($builder);

        $grid->setSorting($sorting);

        foreach ($sorting as $field => $order) {
            if (!isset($fields[$field])) {
                throw new \Exception(sprintf('%s is not a valid field for sorting. Allowed fields : %s', $field, implode(', ', array_keys($fields))));
            }

            $expressionBuilder->addOrderBy($field, $order);
        }
    }
}
