<?php

namespace App\Repository;

use App\Entity\Inscription;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @method Inscriptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscriptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscriptions[]    findAll()
 * @method Inscriptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

    // /**
    //  * @return Inscriptions[] Returns an array of Inscriptions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findByIdSortieAndIdParticipant($sortie, $participant)
    {
        return $this->createQueryBuilder('inscription')
            ->andWhere('inscription.participant = :idParticipant')
            ->andWhere('inscription.sortie = :idSortie')
            ->setParameter('idParticipant', $participant)
            ->setParameter('idSortie', $sortie)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

}
