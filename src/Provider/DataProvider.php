<?php

namespace Sherlockode\CrudBundle\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Sherlockode\CrudBundle\Grid\Grid;
use Symfony\Component\HttpFoundation\Request;

class DataProvider
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var Filtering
     */
    private $filtering;

    /**
     * @var Sorting
     */
    private $sorting;

    /**
     * @param EntityManagerInterface $em
     * @param PaginatorInterface     $paginator
     * @param Filtering              $filtering
     * @param Sorting                $sorting
     */
    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator, Filtering $filtering, Sorting $sorting)
    {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->filtering = $filtering;
        $this->sorting = $sorting;
    }

    /**
     * @param Grid $grid
     *
     * @return PaginationInterface
     */
    public function getData(Grid $grid, Request $request): PaginationInterface
    {
        if (false === isset($grid->getConfig()['config']['class'])) {
            throw new \InvalidArgumentException('Missing class configuration for the grid');
        }

        $query = $grid->getConfig()['grid']['repository']['method'] ?? null;
        $repository = $this->em->getRepository($grid->getConfig()['config']['class']);

        $query = null === $query
            ? $repository->createQueryBuilder('o')
            : $repository->$query()
        ;

        $this->filtering->apply($query, $grid, $request->get('criteria', []));
        $this->sorting->apply($query, $grid, $request->get('sorting', $grid->getSorting()));

        return $this->paginator->paginate($query, $request->query->get('page', 1), $grid->getPageSize());
    }
}
