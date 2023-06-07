<?php

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\EntityFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class EntityFilter implements FilterInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool
    {
        return 'entity' === $type;
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return EntityFilterType::class;
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param string       $data
     *
     * @return void
     */
    public function apply(QueryBuilder $query, string $field, $data): void
    {
        if ('' === $data) {
            return;
        }

        $expressionBuilder = new ExpressionBuilder($query);
        $query->andWhere($expressionBuilder->equals($field, $data));
    }
}
