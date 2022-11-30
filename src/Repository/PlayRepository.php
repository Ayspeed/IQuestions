<?php

namespace App\Repository;

use App\Entity\Play;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Play>
 *
 * @method Play|null find($id, $lockMode = null, $lockVersion = null)
 * @method Play|null findOneBy(array $criteria, array $orderBy = null)
 * @method Play[]    findAll()
 * @method Play[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Play::class);
    }

    public function save(Play $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Play $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Play[] Returns an array of Play objects
    */
   public function findBestPlayers($quizz): array
   {
       return $this->createQueryBuilder('p')
       ->andWhere('p.quizz = :quizz')
       ->setParameter('quizz', $quizz)
           ->orderBy('p.ScoreUser', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   /**
    * @return Play[] Returns an array of Play objects
    */
    public function findAllPlayersOfQuizz($quizz): array
    {
        return $this->createQueryBuilder('p')
        ->andWhere('p.quizz = :quizz')
        ->setParameter('quizz', $quizz)
            ->getQuery()
            ->getResult()
        ;
    }

   public function findIfUserAlreadyPlayed($user, $quizz): ?Play
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.player = :user')
           ->andWhere('p.quizz = :quizz')
           ->setParameter('user', $user)
           ->setParameter('quizz', $quizz)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
