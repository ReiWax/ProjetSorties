<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;

use App\Form\EventFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;

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

        $event = new Event();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $event->getUsers($user);
            $event->setOrganizer($user);
            
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Ajout d un event réussi');
        
            return $this->redirectToRoute('app_home');  
        }

        return $this->render('event/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/event_register/{id}', name: 'app_event_register')]
    public function registerEvent(EntityManagerInterface $em,$id){
        /** @var User $user */
        $user = $this->getUser(); 
        $event =  $this->entityManager->getRepository(Event::class)->find($id);
        $user->addEvent($event);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_home');            
    }

    #[Route('/event_unsubscribe/{id}', name: 'app_event_unsubscribe')]
    public function unsubscribeEvent(EntityManagerInterface $em,$id){
        /** @var User $user */
        $user = $this->getUser(); 
        $event =  $this->entityManager->getRepository(Event::class)->find($id);
        $user->removeEvent($event);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('app_home');            
    }

    #[Route('/event_detail/{id}', name: 'app_event_detail')]
    public function detailEvent(EntityManagerInterface $em,$id){
        $event =  $this->entityManager->getRepository(Event::class)->find($id);
        return $this->render('event/detail.html.twig', ['event' => $event]);
    }

    #[Route('/event_cancel/{id}', name: 'app_event_cancel')]
    public function cancelEvent(EntityManagerInterface $em,$id,Request $request){
        $event = new Event();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('app_home');            
        }
        $event =  $this->entityManager->getRepository(Event::class)->find($id);
        return $this->render('event/modify.html.twig', [ 'form' => $form->createView(),'event' => $event]);
    }

    #[Route('/event_modify/{id}', name: 'app_event_modify')]
    public function modifyEvent(EntityManagerInterface $em,$id,Request $request){
        /** @var User $user */
        $user = $this->getUser();
        $event = $this->entityManager->getRepository(Event::class)->find($id);
        $event->setOrganizer($user);
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            return $this->redirectToRoute('app_home');            
        }
        $event =  $this->entityManager->getRepository(Event::class)->find($id);
        return $this->render('event/modify.html.twig', [ 'form' => $form->createView(),'event' => $event]);
    }

    public function updatePastEvent(EventRepository $repository){
        $events = $repository->findPastEvent();
        $state =  $this->entityManager->getRepository(State::class)->find(6);
        foreach($events as $event){
            $event->setState($state);
        }
    }

}
