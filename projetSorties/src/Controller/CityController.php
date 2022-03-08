<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{

    public function __construct(EntityManagerInterface $emi){
        $this->entityManager = $emi;
    }

    
    #[Route('/city', name: 'app_city')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $city = new City();

        $form = $this->CreateForm(CityFormType::class, $city);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){            
            $em->persist($city);
            $em->flush();
        
            $this->addFlash('success', 'Ajout d une Ville réussi');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('city/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
