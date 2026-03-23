<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Sherlockode\CrudBundle\Grid\Grid;
use Symfony\Component\HttpFoundation\Request;

class DataProvider
{
    private PaginatorInterface $paginator;

    public function __construct(
        private readonly EntityManagerInterface $em,
        PaginatorInterface $paginator,
        private readonly Filtering $filtering,
        private readonly Sorting $sorting,
    ) {
        $this->paginator = $paginator;
    }

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

        return $this->paginator->paginate($query, $request->query->getInt('page', 1), $grid->getPageSize());
    }
}
