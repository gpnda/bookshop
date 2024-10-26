<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Publisher;
use App\Entity\Book;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PublisherController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/api/v1/publishers', name: 'publishers_list', methods: ['GET'])]
    public function index(): JsonResponse
    {

        $publishers = $this->entityManager->getRepository(Publisher::class)->findAll();

        $data = [];
        foreach ($publishers as $publisher) {
            $data[] = $publisher->asArray();
        }
        return $this->json($data);
    }



    #[Route('/api/v1/publisher/{id}', name: 'publisher_info', methods: ['GET'])]
    public function getPublisher(int $id): JsonResponse
    {
        $publisher = $this->entityManager->getRepository(Publisher::class)->find($id);
        
        if ($publisher) {
            $response = $this->json($publisher->asArray());
        } else {
            $response = $this->json(["Publisher not found"], 404);
        }
        return $response;
    }


    #[Route('/api/v1/publisher/{id}', name: 'publisher_delete', methods: ['DELETE'])]
    public function deletePublisher(int $id): JsonResponse
    {
        $publisher = $this->entityManager->getRepository(Publisher::class)->find($id);
        
        if ($publisher) {
            $myid = $publisher->getId();
            $mybooks = $this->entityManager->getRepository(Book::class)->findByPublisherId($myid);
            // защита целостности данных
            if (count($mybooks)){
                $response = $this->json(["Publisher has books and can not be deleted!"], 200);
            } else {
                $this->entityManager->remove($publisher);
                $this->entityManager->flush();
                $response = $this->json(["result" => "Publisher deleted"]);
            }

        } else {
            $response = $this->json(["Publisher not found"], 404);
        }
        return $response;
    }




    #[Route('/api/v1/publisher_modify', name: 'publisher_modify', methods: ['PUT'])]
    public function modifyPublisher(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent());
        
        $publisher = $this->entityManager->getRepository(Publisher::class)->find($data->id);

        if ($publisher) {
            $publisher->setName($data->name);
            $errors = $validator->validate($publisher);
            
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                $response = $this->json($errorsString);
            } else {
                $this->entityManager->persist($publisher);
                $this->entityManager->flush();
                $response = $this->json("Publisher modified");
            }
            
        } else {
            $response = $this->json(["Publisher not found"], 404);
        }
        return $response;
    }



    
}
