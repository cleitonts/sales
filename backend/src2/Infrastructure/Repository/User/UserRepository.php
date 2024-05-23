<?php

namespace Panthir\Infrastructure\Repository\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Panthir\Application\Common\DTO\DTOInterface;
use Panthir\Application\UseCase\User\Normalizer\DTO\UserSearchDTO;
use Panthir\Domain\User\Model\User;
use Panthir\Domain\User\Repository\UserRepositoryInterface;
use Panthir\Infrastructure\Repository\CountableTrait;
use Panthir\Infrastructure\Repository\NotNullTrait;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    use CountableTrait;
    use NotNullTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @param UserSearchDTO $search
     */
    public function search(DTOInterface $search): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where('1=1');

        if (!empty($search->page) && !empty($search->limit)) {
            $qb->setFirstResult(($search->page - 1) * $search->limit)
                ->setMaxResults($search->limit);
        }

        if (!empty($search->email)) {
            $qb->andWhere('u.email = :email')
                ->setParameter(':email', $search->email);
        }

        if (!empty($search->token)) {
            $qb->andWhere('u.passwordResetToken = :token')
                ->setParameter(':token', $search->token);
        }

        $results = $qb->getQuery()->getResult();
        $total = $this->countAllResults($qb, 'u.id');

        if (!empty($results)) {
            $results[0]->setTotalItems($total);
        }

        return $results;
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
    }
}
