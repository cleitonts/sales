<?php

namespace Panthir\Infrastructure\Repository\Sale;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Panthir\Application\UseCase\Sale\Normalizer\DTO\SaleSearchDTO;
use Panthir\Domain\Sale\Model\Sale;
use Panthir\Domain\Sale\Repository\SaleRepositoryInterface;
use Panthir\Infrastructure\Repository\CountableTrait;
use Panthir\Infrastructure\Repository\NotNullTrait;

/**
 * @extends ServiceEntityRepository<Sale>
 *
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleRepository extends ServiceEntityRepository implements SaleRepositoryInterface
{
    use CountableTrait;
    use NotNullTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    public function search(SaleSearchDTO $DTO): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p', 'c')
            ->innerJoin('p.category', 'c')
            ->orderBy('p.name', 'DESC')
            ->where('1=1')
            ->setMaxResults(32);

        if (!empty($params['term'])) {
            $qb->andWhere('LOWER(p.name) like :term')
                ->setParameter(':term', '%'.strtolower($params['term']).'%');
        }

        if (!empty($params['brands'])) {
            $qb->andWhere('p.brand in (:brands)')
                ->setParameter(':brands', $params['brands']);
        }

        if (!empty($params['categories'])) {
            $qb->andWhere('p.category in (:categories)')
                ->setParameter(':categories', $params['categories']);
        }

        if (!empty($params['page'])) {
            $qb->setFirstResult(($params['page'] - 1) * 32);
        }

        $results = $qb->getQuery()->getResult();
        $total = $this->countAllResults($qb, 'p.id');

        if (!empty($results)) {
            $results[0]->setTotalItems($total);
        }

        return $results;
    }

    public function getBrands()
    {
        return $this->createQueryBuilder('p')
            ->select('p.brand')
            ->groupBy('p.brand')
            ->orderBy('p.brand', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
