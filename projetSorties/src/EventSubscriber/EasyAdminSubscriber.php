<?php

namespace App\EventSubscriber;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordEncoder,
        private Security $security,
        )
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['addUser'], 
                ['setOrganizer'],
            ],
            BeforeEntityUpdatedEvent::class => ['updateUser'], //surtout utile lors d'un reset de mot passe plutôt qu'un réel update, car l'update va de nouveau encrypter le mot de passe DEJA encrypté ...
        ];
    }

    public function updateUser(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }
        $this->setPassword($entity);
        if($entity->getAdmin() == true) {
            $entity->setRoles(['ROLE_ADMIN']);
        } else {
            $entity->setRoles(['ROLE_USER']);
        }
        
    }

    public function addUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }
        $this->setPassword($entity);
        if($entity->getAdmin() == true) {
            $entity->setRoles(['ROLE_ADMIN']);
        } else {
            $entity->setRoles(['ROLE_USER']);
        }
    }

    /**
     * @param User $entity
     */
    public function setPassword(User $entity): void
    {
        $pass = $entity->getPassword();

        $entity->setPassword(
            $this->passwordEncoder->hashPassword(
                $entity,
                $pass
            )
        );
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function setOrganizer(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Event)) {
            return;
        }
        /** @var User $user */
        $user = $this->security->getUser();
        $entity->setOrganizer($user);
    }
}