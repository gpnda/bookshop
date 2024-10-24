<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/author_create', name: 'author_create', methods: ['POST','GET'])]
    public function new(Request $request): Response
    {


        // Непонятно, почемуто тут $request->request пустой, а данные прилетают в $request->query
        // профайлер бы вклчить для API как нибудь, посмотреть что там происходит
        $data = $request->request->all();

        //$data = json_decode($request->getContent(), true);
        //$data = $request;
        //$data = [1,2,3];
        
        

        // Обработка данных из запроса (если необходимо)

        $response = new Response(json_encode([$data]), Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;




        // return $this->render('author/index.html.twig', [
        //     'controller_name' => 'AuthorController',
        // ]);
    }
}
