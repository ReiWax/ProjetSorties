<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $emi){
        $this->entityManager = $emi;
    }
    
    #[Route('/location', name: 'app_location')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $location = new Location();

        $form = $this->CreateForm(LocationFormType::class, $location);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){            
            $em->persist($location);
            $em->flush();
        
            $this->addFlash('success', 'Ajout d un Lieux réussi');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('location/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
