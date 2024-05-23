<?php

namespace Panthir\UI\Command\User;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\User\CreatePasswordRecoveryTokenHandler;
use Panthir\Application\UseCase\User\Normalizer\DTO\PasswordRecoveryDTO;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:user-send-reset-password-email',
    description: 'Sends an e-mail to a specific user to reset its password.',
    hidden: false
)]
class UserSendResetPasswordEmailCommand extends Command
{
    public function __construct(
        private readonly CreatePasswordRecoveryTokenHandler $passwordRecoveryHandler,
        private readonly HandlerRunner $handlerRunner
    ) {
        parent::__construct('app:user-send-reset-password-email');
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Sends an e-mail to a specific user to reset its password.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Password reset',
            '============',
            '',
        ]);

        $helper = $this->getHelper('question');
        $question = new Question('Please enter the email of the user [john@doe.com]: ', 'john@doe.com');
        $email = $helper->ask($input, $output, $question);

        $this->handlerRunner->__invoke($this->passwordRecoveryHandler,
            new PasswordRecoveryDTO(
                email: $email,
            )
        );

        return Command::SUCCESS;
    }
}
