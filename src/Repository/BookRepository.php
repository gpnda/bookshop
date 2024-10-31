<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

       /**
        * @return Book[] Returns an array of Book objects
        */
       public function findByAuthorId($author_id): array
       {
        //    return $this->createQueryBuilder('b')
        //        ->andWhere('b.author = ?1')
        //        ->setParameter(1, $author_id)
        //        ->getQuery()
        //        ->getResult()
        //    ;
            return $this->findBy(['author'=>$author_id]);
       }


       public function findByPublisherId($publisher_id): array
       {
        //    return $this->createQueryBuilder('b')
        //        ->andWhere('b.publisher = ?1')
        //        ->setParameter(1, $publisher_id)
        //        ->getQuery()
        //        ->getResult()
        //    ;
            return $this->findBy(['publisher'=>$publisher_id]);
       }


}
