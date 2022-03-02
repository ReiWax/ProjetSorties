<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Event;
use App\Entity\Location;
use App\Entity\State;
use App\Entity\User;

use App\Form\EventFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $emi){
        $this->entityManager = $emi;
    }

    #[Route('/event', name: 'app_event')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {

        /** @var User $user */
        $user = $this->getUser(); 
        // dd($user->getId());

        $event = new Event();
        $adress = $this->entityManager->getRepository(Adress::class)->find(1);
        $location = $this->entityManager->getRepository(Location::class)->find(1);
        $state = $this->entityManager->getRepository(State::class)->find(1);

        $form = $this->createForm(EventFormType::class, $event);

        $event->getUsers($user);
        $event->setOrganizer($user);
        $event->setAdress($adress);
        $event->setLocation($location);
        $event->setState($state);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($event);

            $em->flush();
        
            return $this->redirectToRoute('app_home');            
        }

        return $this->render('event/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
