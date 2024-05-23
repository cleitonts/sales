<?php

namespace Panthir\Infrastructure\Repository\Person;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierSearchDTO;
use Panthir\Domain\Supplier\Model\Supplier;
use Panthir\Domain\Supplier\Repository\SupplierRepositoryInterface;
use Panthir\Infrastructure\Repository\CountableTrait;
use Panthir\Infrastructure\Repository\NotNullTrait;

/**
 * @extends ServiceEntityRepository<Supplier>
 *
 * @method Supplier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supplier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supplier[]    findAll()
 * @method Supplier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplierRepository extends ServiceEntityRepository implements SupplierRepositoryInterface
{
    use CountableTrait;
    use NotNullTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Supplier::class);
    }

    public function search(SupplierSearchDTO $search): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
//            ->select('p, addresses')
//            ->leftJoin('p.addresses', 'addresses')
        ;

        if (!empty($search->page) && !empty($search->limit)) {
            $qb->setFirstResult(($search->page - 1) * $search->limit)
                ->setMaxResults($search->limit);
        }

        $results = $qb->getQuery()->getResult();
        $total = $this->countAllResults($qb, 'p.id');

        if (!empty($results)) {
            $results[0]->setTotalItems($total);
        }

        return $results;
    }
}
