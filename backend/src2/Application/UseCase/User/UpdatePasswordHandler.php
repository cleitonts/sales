<?php

namespace Panthir\Application\UseCase\User;

use Doctrine\ORM\EntityManagerInterface;
use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\Common\Handler\BeforeExecutedHandlerInterface;
use Panthir\Application\UseCase\User\Normalizer\DTO\PasswordRecoveryDTO;
use Panthir\Domain\User\DomainServices\PasswordHashGenerator;
use Panthir\Domain\User\Model\User;
use Panthir\Infrastructure\CommonBundle\Exception\HandlerException;

class UpdatePasswordHandler extends AbstractHandler implements BeforeExecutedHandlerInterface
{
    private ?User $user;

    public function __construct(
        EntityManagerInterface $entityManager,
        private readonly PasswordHashGenerator $passwordHasher,
    ) {
        parent::__construct(entityManager: $entityManager);
    }

    public function supports($object): bool
    {
        return $object instanceof PasswordRecoveryDTO;
    }

    /**
     * @param PasswordRecoveryDTO $model
     *
     * @throws HandlerException
     */
    public function beforeExecuted($model): void
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $model->email,
            'passwordResetToken' => $model->passwordResetToken,
        ]);
        if (empty($user)) {
            throw new HandlerException("Wasn't possible to update user password.", 400);
        }

        $this->user = $user;
    }

    /**
     * @param PasswordRecoveryDTO $model
     */
    public function execute($model): User
    {
        //        $this->user->setPasswordResetToken($this->passwordResetTokenGenerator->__invoke()); null

        $hashedPassword = $this->passwordHasher->__invoke($model->password);
        $this->user->setPassword($hashedPassword);

        $this->entityManager->persist($this->user);

        return $this->user;
    }
}
