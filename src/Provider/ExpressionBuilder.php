<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Provider;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;

class ExpressionBuilder
{
    public function __construct(private readonly QueryBuilder $queryBuilder)
    {
    }

    public function equals(string $field, string $value): Comparison
    {
        $parameterName = $this->getParameterName($field);
        $this->queryBuilder->setParameter($parameterName, $value);

        return $this->queryBuilder->expr()->eq($this->adjustField($field), ':' . $parameterName);
    }

    public function notLike(string $field, string $pattern): Comparison
    {
        return $this->queryBuilder->expr()->notLike(
            (string) $this->queryBuilder->expr()->lower($this->adjustField($field)),
            $this->queryBuilder->expr()->literal(strtolower($pattern))
        );
    }

    public function like(string $field, string $pattern): Comparison
    {
        return $this->queryBuilder->expr()->like(
            (string) $this->queryBuilder->expr()->lower($this->adjustField($field)),
            $this->queryBuilder->expr()->literal(strtolower($pattern))
        );
    }

    /**
     * @param mixed  $value
     *
     */
    public function lessThanOrEqual(string $field, $value): Comparison
    {
        $field = $this->adjustField($field);
        $parameterName = $this->getParameterName($field);
        $this->queryBuilder->setParameter($parameterName, $value);

        return $this->queryBuilder->expr()->lte($field, ':' . $parameterName);
    }

    /**
     * @param mixed  $value
     *
     */
    public function greaterThanOrEqual(string $field, $value): Comparison
    {
        $field = $this->adjustField($field);
        $parameterName = $this->getParameterName($field);
        $this->queryBuilder->setParameter($parameterName, $value);

        return $this->queryBuilder->expr()->gte($field, ':' . $parameterName);
    }

    public function addOrderBy(string $field, string $order): void
    {
        $this->queryBuilder->addOrderBy($this->adjustField($field), $order);
    }

    private function adjustField(string $field): string
    {
        $rootAlias = $this->queryBuilder->getRootAliases()[0];

        return $rootAlias . '.' . $field;
    }

    private function getParameterName(string $field): string
    {
        $parameterName = str_replace('.', '_', $field);

        $i = 1;
        while ($this->hasParameterName($parameterName)) {
            $parameterName .= $i;
        }

        return $parameterName;
    }

    private function hasParameterName(string $parameterName): bool
    {
        return null !== $this->queryBuilder->getParameter($parameterName);
    }
}
