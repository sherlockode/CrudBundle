<?php

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\DateRangeFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class DateRangeFilter implements FilterInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool
    {
        return 'date_range' === $type;
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return DateRangeFilterType::class;
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
        $expressionBuilder = new ExpressionBuilder($query);

        if (isset($data['from']) && '' !== $data['from']) {
            $date = (new \DateTime($data['from']))->setTime(0, 0);
            $query->andWhere($expressionBuilder->greaterThanOrEqual($field, $date));
        }

        if (isset($data['to']) && '' !== $data['to']) {
            $date = (new \DateTime($data['to']))->setTime(23, 59, 59);
            $query->andWhere($expressionBuilder->lessThanOrEqual($field, $date));
        }
    }
}
