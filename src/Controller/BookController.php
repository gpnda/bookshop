<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;



class BookController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/api/v1/books', name: 'books_list', methods: ['GET'])]
    public function index(): JsonResponse
    {

        $books = $this->entityManager->getRepository(Book::class)->findAll();

        $data = [];
        foreach ($books as $book) {
            $data[] = $book->asArray();
        }
        return $this->json($data);
    }


    #[Route('/api/v1/book/{id}', name: 'book_info', methods: ['GET'])]
    public function getBook(int $id): JsonResponse
    {
        $book = $this->entityManager->getRepository(Book::class)->find($id);
        
        if ($book) {
            $response = $this->json($book->asArray());
        } else {
            $response = $this->json(["Book not found"], 404);
        }
        return $response;
    }

    #[Route('/api/v1/book/{id}', name: 'book_delete', methods: ['DELETE'])]
    public function deleteBook(int $id): JsonResponse
    {
        $book = $this->entityManager->getRepository(Book::class)->find($id);
        
        if ($book) {
            $this->entityManager->remove($book);
            $this->entityManager->flush();
            $response = $this->json(["result" => "Book deleted"]);
        } else {
            $response = $this->json(["Book not found"], 404);
        }
        return $response;

    }

}
