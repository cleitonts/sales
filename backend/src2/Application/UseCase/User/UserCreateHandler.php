<?php

namespace Panthir\Application\UseCase\User;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\Common\Handler\AfterExecutedHandlerInterface;
use Panthir\Application\Common\Handler\BeforeExecutedHandlerInterface;
use Panthir\Application\UseCase\User\Normalizer\DTO\RegisterDTO;
use Panthir\Domain\User\DomainServices\PasswordHashGenerator;
use Panthir\Domain\User\DomainServices\PasswordResetTokenGenerator;
use Panthir\Domain\User\Model\User;
use Panthir\Domain\User\ValueObject\UserRoles;
use Panthir\Infrastructure\CommonBundle\Exception\HandlerException;
use Panthir\Infrastructure\Messenger\DTO\EmailNotification;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;

class UserCreateHandler extends AbstractHandler implements BeforeExecutedHandlerInterface, AfterExecutedHandlerInterface
{
    private ?User $user;

    public function __construct(
        EntityManagerInterface $entityManager,
        protected MessageBusInterface $bus,
        private readonly PasswordHashGenerator $passwordHasher,
        protected PasswordResetTokenGenerator $passwordResetTokenGenerator
    ) {
        parent::__construct(entityManager: $entityManager);
    }

    public function supports($object): bool
    {
        return $object instanceof RegisterDTO;
    }

    /**
     * @param RegisterDTO $model
     *
     * @throws HandlerException
     */
    public function beforeExecuted($model): void
    {
        if (!filter_var($model->email, FILTER_VALIDATE_EMAIL)) {
            throw new HandlerException('The given e-mail is not valid.', 400);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $model->email]);
        if (!empty($user)) {
            throw new HandlerException("There's already one user registered with this e-mail.", 400);
        }
        $this->user = $user;
    }

    /**
     * @param RegisterDTO $model
     *
     * @throws JWTEncodeFailureException
     */
    public function execute($model): User
    {
        $resetToken = null;
        if (empty($model->password)) {
            $resetToken = $this->passwordResetTokenGenerator->__invoke();
            $hashedPassword = $this->passwordHasher->__invoke(base64_encode(random_bytes(10)));

            // improvable but not impossible, check if the token already exists and generate another
            $existingToken = $this->entityManager->getRepository(User::class)->findBy(['passwordResetToken' => $resetToken]);
            if (!empty($existingToken)) {
                $resetToken = $this->passwordResetTokenGenerator->__invoke();
            }
        } else {
            $hashedPassword = $this->passwordHasher->__invoke($model->password);
        }

        $this->user = new User(
            uuid: Uuid::uuid4(),
            email: $model->email,
            roles: empty($model->roles) ? UserRoles::PROFILE_VIEWER : $model->roles,
            passwordResetToken: $resetToken,
            password: $hashedPassword
        );

        $this->entityManager->persist($this->user);

        return $this->user;
    }

    /**
     * @param RegisterDTO $model
     */
    public function afterExecuted($model): void
    {
        if (empty($this->user->getPasswordResetToken())) {
            return;
        }

        $this->bus->dispatch(new EmailNotification(
            userEmail: $this->user->getEmail(),
            resetPasswordtoken: $this->user->getPasswordResetToken()
        ));
    }
}
