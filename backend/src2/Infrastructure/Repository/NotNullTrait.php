<?php

namespace Panthir\Infrastructure\Repository;

trait NotNullTrait
{
    public function findNotNull(string $column): array
    {
        $queryBuilder = $this->createQueryBuilder('t');

        return $queryBuilder
            ->where($queryBuilder->expr()->isNotNull("t.$column"))
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneNotNull(string $column): object
    {
        $queryBuilder = $this->createQueryBuilder('t');

        return $queryBuilder
            ->where($queryBuilder->expr()->isNotNull("t.$column"))
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult()
        ;
    }
}
