<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    #[Route('/user/edit', name: 'app_user_edit')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHash, SluggerInterface $slugger): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            
            /** @var UploadedFile $uploadeFile */
            $uploadeFile = $form->get('imageFile')->getData();


            if($uploadeFile) {
                $originalFilename = pathinfo($uploadeFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadeFile->guessExtension();

                try {
                    $uploadeFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    //A remplire si besoin.
                }

                $user->setIllustration($newFilename);
            }
           
            $user->setPassword(
                $userPasswordHash->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
        
            $this->entityManager->flush();
            return $this->redirectToRoute('app_user_profil');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/profil', name: 'app_user_profil')]
    public function profil(Request $request, UserPasswordHasherInterface $userPasswordHash, SluggerInterface $slugger): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('user/profil.html.twig');
    }
}
