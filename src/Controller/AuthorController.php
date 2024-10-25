<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AuthorController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
