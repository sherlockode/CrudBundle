<?php

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\BooleanFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class BooleanFilter implements FilterInterface
{
    public const TRUE = 'true';

    public const FALSE = 'false';

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool
    {
        return 'boolean' === $type;
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return BooleanFilterType::class;
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

        $data = self::TRUE === $data;

        $expressionBuilder = new ExpressionBuilder($query);
        $query->andWhere($expressionBuilder->equals($field, $data));
    }
}
