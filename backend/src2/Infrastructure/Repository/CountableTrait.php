<?php

namespace Panthir\Infrastructure\Repository;

use Doctrine\ORM\QueryBuilder;

trait CountableTrait
{
    public function countAllResults(QueryBuilder $queryBuilder, string $idAlias = 't.id'): int
    {
        return $queryBuilder->select("count($idAlias)")
            ->resetDQLParts(['orderBy', 'groupBy'])
            ->setFirstResult(null)
            ->setMaxResults(null)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
