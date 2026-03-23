<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Grid;

use Knp\Component\Pager\Pagination\PaginationInterface;

class GridView
{
    public function __construct(
        private readonly PaginationInterface $data,
        private readonly Grid $grid,
    ) {
    }

    /**
     * @return array
     */
    public function getData(): PaginationInterface
    {
        return $this->data;
    }

    public function getGrid(): Grid
    {
        return $this->grid;
    }
}
