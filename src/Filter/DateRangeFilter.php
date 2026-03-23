<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\DateRangeFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class DateRangeFilter implements FilterInterface
{
    public function supports(string $type): bool
    {
        return 'date_range' === $type;
    }

    public function getFormType(): string
    {
        return DateRangeFilterType::class;
    }

    /**
     * @param array        $data
     *
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
