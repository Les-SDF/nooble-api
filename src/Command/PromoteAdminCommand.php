<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Roles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


#[AsCommand(
    name: 'promote:admin',
    description: 'Promote a user to admin',
)]
class PromoteAdminCommand extends Command
{
    public function __construct(private UserRepository $repository,
                                private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument("user_mail", InputArgument::REQUIRED, "User mail")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user_mail = $input->getArgument("user_mail");

        /**
         * @var User $user
         */
        $user = $this->repository->findOneBy(['email' => $user_mail]);
        if (!$user) {
            $io->error(sprintf("User %s not found", $user_mail));
            return Command::INVALID;
        }
        if (in_array(Roles::ADMIN, $user->getRoles())) {
            $io->error(sprintf("User %s is already admin", $user->getEmail()));
            return Command::INVALID;
        }
        $user->addRole(Roles::ADMIN);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $io->success(sprintf("User %s is now admin", $user->getEmail()));
        return Command::SUCCESS;
    }
}