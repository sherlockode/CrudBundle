<?php

namespace Sherlockode\CrudBundle\Provider;


use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;

class ExpressionBuilder
{
    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @param string $field
     * @param string $value
     *
     * @return Comparison
     */
    public function equals(string $field, string $value): Comparison
    {
        $parameterName = $this->getParameterName($field);
        $this->queryBuilder->setParameter($parameterName, $value);

        return $this->queryBuilder->expr()->eq($this->adjustField($field), ':' . $parameterName);
    }

    /**
     * @param string $field
     * @param string $pattern
     *
     * @return Comparison
     */
    public function notLike(string $field, string $pattern): Comparison
    {
        $parameterName = $this->getParameterName($field);

        return $this->queryBuilder->expr()->notLike(
            (string) $this->queryBuilder->expr()->lower($this->adjustField($field), ':' . $parameterName),
            $this->queryBuilder->expr()->literal(strtolower($pattern))
        );
    }

    /**
     * @param string $field
     * @param string $pattern
     *
     * @return Comparison
     */
    public function like(string $field, string $pattern): Comparison
    {
        $parameterName = $this->getParameterName($field);

        return $this->queryBuilder->expr()->like(
            (string) $this->queryBuilder->expr()->lower($this->adjustField($field), ':' . $parameterName),
            $this->queryBuilder->expr()->literal(strtolower($pattern))
        );
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return Comparison
     */
    public function lessThanOrEqual(string $field, $value): Comparison
    {
        $field = $this->adjustField($field);
        $parameterName = $this->getParameterName($field);
        $this->queryBuilder->setParameter($parameterName, $value);

        return $this->queryBuilder->expr()->lte($field, ':' . $parameterName);
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return Comparison
     */
    public function greaterThanOrEqual(string $field, $value): Comparison
    {
        $field = $this->adjustField($field);
        $parameterName = $this->getParameterName($field);
        $this->queryBuilder->setParameter($parameterName, $value);

        return $this->queryBuilder->expr()->gte($field, ':' . $parameterName);
    }

    /**
     * @param string $field
     * @param string $order
     *
     * @return void
     */
    public function addOrderBy(string $field, string $order): void
    {
        $this->queryBuilder->addOrderBy($this->adjustField($field), $order);
    }

    /**
     * @param string $field
     *
     * @return string
     */
    private function adjustField(string $field): string
    {
        $rootAlias = $this->queryBuilder->getRootAliases()[0];

        return $rootAlias . '.' . $field;
    }

    /**
     * @param string $field
     *
     * @return string
     */
    private function getParameterName(string $field): string
    {
        $parameterName = str_replace('.', '_', $field);

        $i = 1;
        while ($this->hasParameterName($parameterName)) {
            $parameterName .= $i;
        }

        return $parameterName;
    }

    /**
     * @param string $parameterName
     *
     * @return bool
     */
    private function hasParameterName(string $parameterName): bool
    {
        return null !== $this->queryBuilder->getParameter($parameterName);
    }
}
