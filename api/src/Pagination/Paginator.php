<?php

namespace App\Pagination;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

final class Paginator
{
    final public const PAGE_SIZE = 10;

    private int $currentPage;
    private int $numResults;

    /**
     * @var \Traversable<int, object>
     */
    private \Traversable $results;

    public function __construct(
        private readonly DoctrineQueryBuilder $queryBuilder,
        private int $pageSize = self::PAGE_SIZE
    ) {
    }

    public function paginate(int $page = 1): self
    {
        $this->currentPage = max(1, $page);
        $firstResult = ($this->currentPage - 1) * $this->pageSize;

        $query = $this->queryBuilder
            ->setFirstResult($firstResult)
            ->setMaxResults($this->getPageSize())
            ->getQuery();

        // @INFO: Set Hydration to Array, to fit on JSON response
        $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        /** @var array<string, mixed> $joinDqlParts */
        $joinDqlParts = $this->queryBuilder->getDQLPart('join');

        if (0 === \count($joinDqlParts)) {
            $query->setHint(CountWalker::HINT_DISTINCT, false);
        }

        $paginator = new DoctrinePaginator($query, true);

        /** @var array<string, mixed> $havingDqlParts */
        $havingDqlParts = $this->queryBuilder->getDQLPart('having');

        $useOutputWalkers = \count($havingDqlParts ?: []) > 0;
        $paginator->setUseOutputWalkers($useOutputWalkers);

        $this->results = $paginator->getIterator();
        $this->numResults = $paginator->count();

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getLastPage(): int
    {
        return (int) ceil($this->numResults / $this->pageSize);
    }

    public function setPageSize(int $pageSize = 10): self
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function getPreviousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getLastPage();
    }

    public function getNextPage(): int
    {
        return min($this->getLastPage(), $this->currentPage + 1);
    }

    public function hasToPaginate(): bool
    {
        return $this->numResults > $this->pageSize;
    }

    public function getNumResults(): int
    {
        return $this->numResults;
    }

    /**
     * @return \Traversable<int, object>
     */
    public function getResults(): \Traversable
    {
        return $this->results;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
