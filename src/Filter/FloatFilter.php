<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\FloatFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class FloatFilter implements FilterInterface
{
    public function supports(string $type): bool
    {
        return 'float' === $type;
    }

    public function getFormType(): string
    {
        return FloatFilterType::class;
    }

    /**
     * @param array        $data
     *
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
            $query->andWhere($expressionBuilder->lessThanOrEqual($field, (float) $lessThanOrEqual));
        }

        if ('' !== $greaterThanOrEqual) {
            $query->andWhere($expressionBuilder->greaterThanOrEqual($field, (float) $greaterThanOrEqual));
        }
    }
}
