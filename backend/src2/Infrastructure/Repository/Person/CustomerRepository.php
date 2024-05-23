<?php

namespace Panthir\Infrastructure\Repository\Person;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerSearchDTO;
use Panthir\Domain\Customer\Model\Customer;
use Panthir\Domain\Customer\Repository\CustomerRepositoryInterface;
use Panthir\Infrastructure\Repository\CountableTrait;
use Panthir\Infrastructure\Repository\NotNullTrait;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository implements CustomerRepositoryInterface
{
    use CountableTrait;
    use NotNullTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function search(CustomerSearchDTO $search): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
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
