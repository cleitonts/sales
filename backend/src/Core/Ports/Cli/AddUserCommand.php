<?php

declare(strict_types=1);

namespace App\Core\Ports\Cli;

use App\Core\Application\Command\User\CreateUser\CreateUserCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class AddUserCommand extends Command
{
    use HandleTrait;

    protected static $defaultName = 'app:create-user';

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $question = new Question('Please enter the username: ');
        $userName = (string)$helper->ask($input, $output, $question);

        $question = new Question('Please enter the password: ');
        $question->setHidden(true);
        $password = (string)$helper->ask($input, $output, $question);

        $question = new Question('Please repeat the password: ');
        $question->setHidden(true);
        $passwordRepeat = (string)$helper->ask($input, $output, $question);

        try {
            $this->handle(new CreateUserCommand($userName, $password, $passwordRepeat));
        } catch (HandlerFailedException $e) {
            $output->writeln('<fg=yellow>Error: </><fg=red>' . $e->getPrevious()->getMessage() . '</>');
            return 1;
        } catch (\Exception $e) {
            $output->writeln('<fg=yellow>Error: </><fg=red>' . $e->getMessage() . '</>');
            return 1;
        }

        $output->writeln('<info>User created</info>');
        return 0;
    }
}
