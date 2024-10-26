<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use App\Entity\Book;

#[AsCommand(
    name: 'clean-up-authors',
    description: 'Delete all authors without any books',
)]
class CleanUpAuthorsCommand extends Command
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $deleted = 0;
        $authors = $this->entityManager->getRepository(Author::class)->findAll();

        foreach ($authors as $author) {
            $myid = $author->getId();
            $mybooks = $this->entityManager->getRepository(Book::class)->findByAuthorId($myid);
            
            if (count($mybooks)){
                $io->success( $author->getId() . " " . $author->getName() . " is author of " . count($mybooks) . " books");
            } else {
                $io->error( $author->getId() . " " . $author->getName() . " has no books amd will be deleted");
                $this->entityManager->remove($author);
                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;


    }
}
