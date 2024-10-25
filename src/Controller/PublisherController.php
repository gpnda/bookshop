<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Publisher;
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
            // Здесь надо проверить что можно безопасно удалить издателя, без нарушения целостности данных
            $this->entityManager->remove($publisher);
            $this->entityManager->flush();
            $response = $this->json(["result" => "Publisher deleted"]);
        } else {
            $response = $this->json(["Publisher not found"], 404);
        }
        return $response;
    }


    
}
