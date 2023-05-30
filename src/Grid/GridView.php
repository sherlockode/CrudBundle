<?php

namespace Sherlockode\CrudBundle\Grid;

use Knp\Component\Pager\Pagination\PaginationInterface;

class GridView
{
    /**
     * @var PaginationInterface
     */
    private $data;

    /**
     * @var Grid
     */
    private $grid;

    /**
     * @param PaginationInterface $data
     * @param Grid                $grid
     */
    public function __construct(PaginationInterface $data, Grid $grid)
    {
        $this->data = $data;
        $this->grid = $grid;
    }

    /**
     * @return array
     */
    public function getData(): PaginationInterface
    {
        return $this->data;
    }

    /**
     * @return Grid
     */
    public function getGrid(): Grid
    {
        return $this->grid;
    }
}
