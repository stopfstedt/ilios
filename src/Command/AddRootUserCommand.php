<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\UserInterface;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Grants root-level privileges to a given user.
 *
 * Class AddRootUserCommand
 */
class AddRootUserCommand extends Command
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'ilios:add-root-user';

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setAliases(['ilios:maintenance:add-root-user'])
            ->setDescription('Grants root-level privileges to a given user.')
            ->addArgument(
                'userId',
                InputArgument::REQUIRED,
                "The user's id."
            );
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');
        /* @var UserInterface $user */
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        if (!$user) {
            throw new \Exception("No user with id #{$userId} was found.");
        }
        $user->setRoot(true);
        $this->userRepository->update($user, true, true);
        $output->writeln("User with id #{$userId} has been granted root-level privileges.");

        return 0;
    }
}
