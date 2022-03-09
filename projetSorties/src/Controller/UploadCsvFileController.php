<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\CsvFile;
use App\Entity\User;
use App\Form\CsvFileType;
use Doctrine\Common\Annotations\Reader as AnnotationsReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use League\Csv\Reader;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UploadCsvFileController extends AbstractController
{
    #[Route('/upload/csv/file', name: 'app_upload_csv_file')]
    public function index(Request $request, SluggerInterface $slugger, UserPasswordHasherInterface $userPasswordHash, EntityManagerInterface $em): Response
    {

        $upload = new CsvFile();
        $form = $this->createForm(CsvFileType::class, $upload);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()) {


           

            /** @var UploadedFile $uploadeFile */
            $uploadeFile = $form->get('csvFilename')->getData();
            //dd($uploadeFile);

            if ($uploadeFile) {
                $originalFilename = pathinfo($uploadeFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadeFile->getClientOriginalExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $uploadeFile->move(
                        $this->getParameter('csv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // TODO
                }

                $upload->setCsvFilename($newFilename);


                $karnelRootDir = $this->getParameter('kernel.project_dir');
                $reader = Reader::createFromPath($karnelRootDir.'/public/uploads/csv/'.$newFilename);
                $result = $reader->getRecords();
                
                foreach ($result as $i => $row ) {

                    
                    $address = $em->getRepository(Adress::class)->find($row[9]);
                   
                    $user = new User();
                    $user
                    ->setEmail($row[0])
                    ->setName($row[1])
                    ->setLastname($row[2])
                    ->setPassword($userPasswordHash->hashPassword(
                        $user,
                        $row[3])
                    )
                    ->setPseudo($row[4])
                    ->setTel((int)$row[5])
                    ->setAdmin((int)$row[6])
                    ->setActive((int)$row[7])
                    ->setIllustration($row[8])
                    ->setAdress($address)

                ;
                     $em->persist($user);
                }
                    $em->flush();
                    $this->addFlash('success','Bien ajouté avec succès');
            }
            
        }


        return $this->render('upload_csv_file/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
