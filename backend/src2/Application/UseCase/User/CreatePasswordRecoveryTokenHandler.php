<?php

namespace Panthir\Application\UseCase\User;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\Common\Handler\AfterExecutedHandlerInterface;
use Panthir\Application\Common\Handler\BeforeExecutedHandlerInterface;
use Panthir\Application\UseCase\User\Normalizer\DTO\PasswordRecoveryDTO;
use Panthir\Domain\User\DomainServices\PasswordResetTokenGenerator;
use Panthir\Domain\User\Model\User;
use Panthir\Infrastructure\CommonBundle\Exception\HandlerException;
use Panthir\Infrastructure\Messenger\DTO\EmailNotification;
use Symfony\Component\Messenger\MessageBusInterface;

class CreatePasswordRecoveryTokenHandler extends AbstractHandler implements BeforeExecutedHandlerInterface, AfterExecutedHandlerInterface
{
    private ?User $user;

    public function __construct(
        EntityManagerInterface $entityManager,
        protected MessageBusInterface $bus,
        protected PasswordResetTokenGenerator $passwordResetTokenGenerator
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
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $model->email]);
        if (empty($user)) {
            throw new HandlerException("Wasn't possible to send the E-mail to reset user password.", 400);
        }

        $this->user = $user;
    }

    /**
     * @param PasswordRecoveryDTO $userDTO
     *
     * @throws JWTEncodeFailureException
     */
    public function execute($userDTO): User
    {
        $this->user->setPasswordResetToken($this->passwordResetTokenGenerator->__invoke());

        $this->entityManager->persist($this->user);

        return $this->user;
    }

    /**
     * @param PasswordRecoveryDTO $model
     */
    public function afterExecuted($model): void
    {
        $this->bus->dispatch(new EmailNotification(
            userEmail: $this->user->getEmail(),
            resetPasswordtoken: $this->user->getPasswordResetToken()
        ));
    }
}
