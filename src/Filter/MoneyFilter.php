<?php

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\MoneyFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class MoneyFilter implements FilterInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool
    {
        return 'money' === $type;
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return MoneyFilterType::class;
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
        $lessThanOrEqual = $data['lessThanOrEqual'];
        $greaterThanOrEqual = $data['greaterThanOrEqual'];

        if ('' === $greaterThanOrEqual && '' === $lessThanOrEqual) {
            return;
        }

        $expressionBuilder = new ExpressionBuilder($query);

        if ('' !== $lessThanOrEqual) {
            $query->andWhere($expressionBuilder->lessThanOrEqual($field, (int) round((float) $lessThanOrEqual * 100)));
        }

        if ('' !== $greaterThanOrEqual) {
            $query->andWhere($expressionBuilder->greaterThanOrEqual($field, (int) round((float) $greaterThanOrEqual * 100)));
        }
    }
}
