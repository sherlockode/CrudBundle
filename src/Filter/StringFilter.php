<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Form\Type\Filter\StringFilterType;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class StringFilter implements FilterInterface
{
    const TYPE_EQUAL = 'equal';

    const TYPE_CONTAINS = 'contains';

    const TYPE_NOT_CONTAINS = 'not_contains';

    public function supports(string $type): bool
    {
        return 'string' === $type;
    }

    public function getFormType(): string
    {
        return StringFilterType::class;
    }

    /**
     * @param array        $data
     *
     */
    public function apply(QueryBuilder $query, string $field, $data): void
    {
        if ('' === $data['value']) {
            return;
        }

        $query->andWhere($this->getExpression($query, $field, $data));
    }

    /**
     *
     *
     * @throws \Exception
     */
    private function getExpression(QueryBuilder $query, string $field, array $data): Comparison
    {
        $expressionBuilder = new ExpressionBuilder($query);
        return match ($data['type']) {
            self::TYPE_EQUAL => $expressionBuilder->equals($field, $data['value']),
            self::TYPE_CONTAINS => $expressionBuilder->like($field, '%' . $data['value'] . '%'),
            self::TYPE_NOT_CONTAINS => $expressionBuilder->notLike($field, '%' . $data['value'] . '%'),
            default => throw new \Exception(sprintf('Expression type %s does not exist', $data['type'])),
        };
    }
}
