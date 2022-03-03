<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Data\SearchData;
use App\Entity\Event;
use App\Form\SearchFormType;
use Doctrine\ORM\EntityManager;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EventRepository $repository, Request $request): Response
    {
      /** @var User $user */ 
      $user = $this->getUser();
      $data = new SearchData();
      $form = $this->createForm(SearchFormType::class, $data);
      $form->handleRequest($request);
      $events = $repository->findSearch($data,$user);  
 
      return $this->render('home/index.html.twig', ['events' => $events, 'form' => $form->createView()]);
    }
}
