<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AuthorController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    #[Route('/api/v1/authors', name: 'authors_list', methods: ['GET'])]
    public function index(): JsonResponse
    {

        $authors = $this->entityManager->getRepository(Author::class)->findAll();

        $data = [];
        foreach ($authors as $author) {
            $data[] = $author->asArray();
        }
        return $this->json($data);
    }


    #[Route('/api/v1/author/{id}', name: 'author_delete', methods: ['DELETE'])]
    public function deleteAuthor(int $id): JsonResponse
    {
        $author = $this->entityManager->getRepository(Author::class)->find($id);
        
        if ($author) {
            $myid = $author->getId();
            $mybooks = $this->entityManager->getRepository(Book::class)->findByAuthorId($myid);
            // защита целостности данных
            if (count($mybooks)){
                $response = $this->json(["Author has books and can not be deleted!"], 200);
            } else {
                $this->entityManager->remove($author);
                $this->entityManager->flush();
                $response = $this->json(["result" => "Author deleted"]);
            }
            
        } else {
            $response = $this->json(["Author not found"], 404);
        }
        return $response;
    }

    #[Route('/api/v1/author/{id}', name: 'author_info', methods: ['GET'])]
    public function getAuthor(int $id): JsonResponse
    {
        $author = $this->entityManager->getRepository(Author::class)->find($id);
        
        if ($author) {
            $response = $this->json($author->asArray());
        } else {
            $response = $this->json(["Author not found"], 404);
        }
        return $response;
    }





/*  
{
    "name":"Roger Zelazny"
}
*/
    #[Route('/api/v1/author_create', name: 'author_create', methods: ['POST'])]
    public function new(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent());
        $author =  new Author();
        $author->setName($data->name);
        $errors = $validator->validate($author);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            $response = $this->json($errorsString);
        } else {
            $this->entityManager->persist($author);
            $this->entityManager->flush();
            $response = $this->json("Ok");
        }
        return $response;

    }
}
