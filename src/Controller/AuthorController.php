<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
class AuthorController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/author_create', name: 'author_create', methods: ['POST'])]
    public function new(Request $request): Response
    {

        
        

        // Непонятно, почемуто тут $request->request пустой, а данные прилетают в $request->query
        // профайлер бы вклчить для API как нибудь, посмотреть что там происходит
        $data = json_decode($request->getContent());
        $author =  new Author();
        $author->setName($data->name);
        $this->entityManager->persist($author);
        $this->entityManager->flush();

        
        

        // Обработка данных из запроса (если необходимо)

        $response = new Response(null, Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;




        // return $this->render('author/index.html.twig', [
        //     'controller_name' => 'AuthorController',
        // ]);
    }
}
