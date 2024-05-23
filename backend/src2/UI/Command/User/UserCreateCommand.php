<?php

namespace Panthir\UI\Command\User;

use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\User\Normalizer\DTO\RegisterDTO;
use Panthir\Application\UseCase\User\UserCreateHandler;
use Panthir\Domain\User\ValueObject\UserRoles;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:user-create',
    description: 'Creates a new user.',
    aliases: ['app:user-add'],
    hidden: false
)]
class UserCreateCommand extends Command
{
    public function __construct(
        private readonly UserCreateHandler $userCreateHandler,
        private readonly HandlerRunner $handlerRunner
    ) {
        parent::__construct('app:user-create');
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        $helper = $this->getHelper('question');
        $question = new Question('Please enter the email of the user [john@doe.com]: ', 'john@doe.com');
        $email = $helper->ask($input, $output, $question);

        $question = new Question('Please enter the password of the user [admin]: ', 'admin');
        $password = $helper->ask($input, $output, $question);

        if (!$helper->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        $output->writeln('Username: '.$email);

        $this->handlerRunner->__invoke($this->userCreateHandler,
            new RegisterDTO(
                email: $email,
                roles: UserRoles::PROFILE_ADMIN,
                password: $password
            )
        );

        return Command::SUCCESS;
    }
}
