<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Publisher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;



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


/*
{
    "title":"vvvvvvvv vtttttt",
    "author":"1",
    "year":"2024",
    "publisher":"1",
    "description":"jfihfdslfh ahfdsl fhla hernfv reoiavnoirea nvoiera"
}
*/
    #[Route('/api/v1/book_create', name: 'book_create', methods: ['POST'])]
    public function createBook(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent());
        $book =  new Book();

        $book->setTitle($data->title);
        
        $author = $this->entityManager->getRepository(Author::class)->find($data->author);
        $book->setAuthor($author);
        
        $book->setYear($data->year);
        
        $publisher = $this->entityManager->getRepository(Publisher::class)->find($data->publisher);
        $book->setPublisher($publisher);
        
        $book->setDescription($data->description);

        $errors = $validator->validate($book);


        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            $response = $this->json($errorsString);
        } else {
            $this->entityManager->persist($book);
            $this->entityManager->flush();
            $response = $this->json("Book added");
        }
        return $response;

    }


    

}
