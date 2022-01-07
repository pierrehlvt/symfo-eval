<?php

namespace App\Controller;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    private EntityManagerInterface $manager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;   
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
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        $media = new Media();
        
        if(isset($data)) {
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
}
