<?php

namespace App\Command;

use App\Service\UpdateInstagramFollowersServiceInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-followers',
    description: 'This command allows you to update instagram followers of a user',
)]
class UpdateFollowersCommand extends Command
{
    public function __construct(
        private readonly UpdateInstagramFollowersServiceInterface $instagramFollowersService,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->instagramFollowersService->updateInstagramFollowersNumber();
            $io->success('Followers updated successfully');
        } catch (Exception $e) {
            $io->error($e->getMessage());
            $this->logger->error('An error occurred while updating followers ', [$e->getMessage()]);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
