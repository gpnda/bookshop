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
use Faker\Factory;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;




#[AsCommand(
    name: 'seed',
    description: 'Fill up DB with random data',
)]
class SeedCommand extends Command
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

        
        $faker = Factory::create();
        $autors =  [];
        for ($i = 0; $i < 20; $i++) {
            $author = new Author();
            $author->setName($faker->firstName() . " " . $faker->lastName());
            $this->entityManager->persist($author);
            array_push($autors, $author);
        }

        $publishers =  [];
        for ($i = 0; $i < 10; $i++) {
            $publisher = new Publisher();
            $publisher->setName($faker->company() . " " . $faker->companySuffix());
            $this->entityManager->persist($publisher);
            array_push($publishers, $publisher);
        }

        for ($i = 0; $i < 30; $i++) {
            $book = new Book();
            $book->setYear($faker->year($max = 'now'));
            $book->setTitle(ucfirst($faker->words($nb = 2, $asText = true)));
            $book->setDescription(ucfirst($faker->words($nb = 20, $asText = true)));
            $book->setAuthor($autors[array_rand($autors)]);
            $book->setPublisher($publishers[array_rand($publishers)]);
            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        $io->success('Success. DB seeded.');

        return Command::SUCCESS;
    }
}
