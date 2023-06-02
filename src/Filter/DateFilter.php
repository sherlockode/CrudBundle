<?php

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\DateFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class DateFilter implements FilterInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool
    {
        return 'date' === $type;
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return DateFilterType::class;
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param array        $data
     *
     * @return void
     */
    public function apply(QueryBuilder $query, string $field, $data): void
    {
        if (!isset($data['date']) || '' === $data['date']) {
            return;
        }

        $expressionBuilder = new ExpressionBuilder($query);
        $query->andWhere($expressionBuilder->like($field, $data['date'] . '%'));
    }
}
