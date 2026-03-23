<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\EntityFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class EntityFilter implements FilterInterface
{
    public function supports(string $type): bool
    {
        return 'entity' === $type;
    }

    public function getFormType(): string
    {
        return EntityFilterType::class;
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

        $expressionBuilder = new ExpressionBuilder($query);
        $query->andWhere($expressionBuilder->equals($field, $data));
    }
}
