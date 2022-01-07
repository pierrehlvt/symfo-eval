<?php

namespace App\Controller;

use App\Entity\Media;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    private EntityManagerInterface $manager;
    private SerializerService $serializerService;
    
    public function __construct(EntityManagerInterface $manager, SerializerService $serializerService)
    {
        $this->manager = $manager;   
        $this->serializerService = $serializerService;
    }

    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/create", name="api_create")
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $media = new Media();
        
        if(isset($data) && !empty($data['nom']) && !empty($data['synopsis']) && !empty($data['type'])) {
            $media->setNom($data['nom']);
            $media->setSynopsis($data['synopsis']);
            $media->setType($data['type']);
            $media->setCreatedAt(new \DateTime());
            
            $this->manager->persist($media);
            $this->manager->flush();
            
            return new JsonResponse("Votre film / série a bien été ajouté", Response::HTTP_CREATED);
        } else {
            return new JsonResponse("Une erreur est survenue, veuillez réessayer", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/api/get-all", name="api_get_all")
     */
    public function getAll(): JsonResponse
    {
        return JsonResponse::fromJsonString($this->serializerService->SimpleSerializer($this->manager->getRepository(Media::class)->findAll(), 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/api/get/{id_item}", name="get_one")
     */
    public function getOneById($id_item): JsonResponse
    {
        if(!empty($id_item)) {
            return JsonResponse::fromJsonString($this->serializerService->SimpleSerializer($this->manager->getRepository(Media::class)->findOneBy(['id' => $id_item]), 'json'), Response::HTTP_OK);
        } else {
            return new JsonResponse("Erreur lors du chargement de la page du film", Response::HTTP_BAD_REQUEST);
        }
    }
}
