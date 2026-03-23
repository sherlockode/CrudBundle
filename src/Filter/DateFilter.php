<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\DateFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class DateFilter implements FilterInterface
{
    public function supports(string $type): bool
    {
        return 'date' === $type;
    }

    public function getFormType(): string
    {
        return DateFilterType::class;
    }

    /**
     * @param array        $data
     *
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
