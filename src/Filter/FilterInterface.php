<?php

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool;

    /**
     * @return string
     */
    public function getFormType(): string;

    /**
     * @param QueryBuilder $query
     * @param string       $key
     * @param mixed        $data
     *
     * @return void
     */
    public function apply(QueryBuilder $query, string $key, $data): void;
}
