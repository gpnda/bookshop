<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;



// 2121
class BookController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/books', name: 'app_book')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BookController.php',
        ]);
    }


    #[Route('/book/{id}', name: 'app_book')]
    public function getBook(int $id): JsonResponse
    {
        $book = $this->entityManager->getRepository(Book::class)->find($id);
        
        

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        return $this->json([
            'message' => 'Welcome to your new controller!' . $book->getTitle() ,
            'path' => 'src/Controller/BookController.php',
        ]);




    }

}
