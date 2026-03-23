<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\BooleanFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class BooleanFilter implements FilterInterface
{
    public const TRUE = 'true';

    public const FALSE = 'false';

    public function supports(string $type): bool
    {
        return 'boolean' === $type;
    }

    public function getFormType(): string
    {
        return BooleanFilterType::class;
    }

    /**
     * @param string       $data
     *
     */
    public function apply(QueryBuilder $query, string $field, $data): void
    {
        if ('' === $data) {
            return;
        }

        $data = self::TRUE === $data;

        $expressionBuilder = new ExpressionBuilder($query);
        $query->andWhere($expressionBuilder->equals($field, $data));
    }
}
