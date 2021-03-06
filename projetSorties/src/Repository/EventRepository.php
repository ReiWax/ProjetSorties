<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Data\SearchData;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Event $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Event $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findPastEvent()
    {
        return $this->createQueryBuilder('e')
        ->where('e.dateTimeStartAt < CURRENT_DATE()')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
    }

    /**
     * Récupère les sorties en lien avec une recherche
     */
    public function findSearch(SearchData $search,$user)
    {

        $qb = $this
        ->createQueryBuilder('e')
        ->select('u', 'e')
        ->join('e.organizer', 'u')
        ->join('e.state', 's');

        if(empty($search->name) &&
            empty($search->dateTimeStartAt) &&
            empty($search->eventIsNotRegistered) &&
            empty($search->eventIsRegistered) &&
            empty($search->dateTimeStartAt) &&
            empty($search->dateLimitRegistrationAt) &&
            empty($search->eventIsOrganizer) &&
            empty($search->eventFinished) &&
            empty($search->adress)){
            $qb = $qb
                ->where("DATE_ADD(e.dateTimeStartAt,1,'MONTH') > :now")
                ->setParameter('now',new \DateTime('now'))
            ;
        }
        if (!empty($search->name)) {
            $qb = $qb
                ->andWhere("DATE_ADD(e.dateTimeStartAt,1,'MONTH') > :now")
                ->setParameter('now',new \DateTime('now'))
                ->andWhere('e.name LIKE :name')
                ->setParameter('name', "%{$search->name}%");
        }

        if (!empty($search->dateTimeStartAt) && !empty($search->dateFinishAt)) {
            $qb = $qb
                ->andWhere('e.dateTimeStartAt >= :dateTimeStartAt')
                ->andWhere('e.dateTimeStartAt <= :dateFinishAt')
                ->setParameter('dateTimeStartAt', $search->dateTimeStartAt->format('Y-m-d h:i:s'))
                ->setParameter('dateFinishAt', $search->dateFinishAt->format('Y-m-d h:i:s'));
        }
        

        if(!empty($search->eventIsOrganizer)){
            $qb = $qb
            ->andWhere("DATE_ADD(e.dateTimeStartAt,1,'MONTH') > :now")
            ->setParameter('now',new \DateTime('now'))
            ->andWhere('u.name = :name')
            ->setParameter('name', $user->getName());
        }

        if(!empty($search->eventFinished)){
            $qb = $qb
            ->andWhere('e.dateTimeStartAt < CURRENT_DATE()');
        }

        if(!empty($search->eventIsRegistered) && empty($search->eventIsNotRegistered)){
            $qb = $qb
            ->join('e.users','user')
            ->andWhere("DATE_ADD(e.dateTimeStartAt,1,'MONTH') > :now")
            ->setParameter('now',new \DateTime('now'))
            ->andWhere('user.id = :user')
            ->
            setParameter('user',$user->getId());
        }

        if(!empty($search->adress)){
            $qb = $qb
            ->andWhere('e.adress= :adress')
            ->setParameter('adress',$search->adress)
            ->andWhere("DATE_ADD(e.dateTimeStartAt,1,'MONTH') > :now")
            ->setParameter('now',new \DateTime('now'))
            ;
        }

        //On récupère tous les events, puis on fait un soustraction pour voir les évènements auxquels je ne suis pas inscrit
        if(!empty($search->eventIsNotRegistered) && empty($search->eventIsRegistered)){
            $qb = $qb
            ->andWhere("DATE_ADD(e.dateTimeStartAt,1,'MONTH') > :now")
            ->setParameter('now',new \DateTime('now'))
            ->andWhere(
                $qb->expr()->notIn(
                    'e.id',
                    $this
                        ->createQueryBuilder('e2')
                        ->select('e2.id')
                        ->join('e2.users','user')
                        ->where('user.id = :user')
                        ->getDQL() 
                )
            )->setParameter(':user',$user->getId());            
        }

        $query = $qb->getQuery();
        return $query->getResult();
    }
}
