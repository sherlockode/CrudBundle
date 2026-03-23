<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
    public function supports(string $type): bool;

    public function getFormType(): string;

    /**
     * @param mixed        $data
     *
     */
    public function apply(QueryBuilder $query, string $key, $data): void;
}
