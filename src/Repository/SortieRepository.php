<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method Sorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorties[]    findAll()
 * @method Sorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @return Sorties[] Returns an array of Sorties objects
     */
    public function findAllSortie()
    {
        $dateSubstract1Month = new \DateTime();
        $dateSubstract1Month->modify('-1 months');

        $dateNow = new \DateTime();

        $qb = $this->createQueryBuilder('s');
        $qb->select(array('s', 'i', 'e', 'l', 'o','p'));
        $qb->leftJoin('s.inscriptions', 'i');
        $qb->leftJoin('i.participant', 'p');
        $qb->leftJoin('s.etat', 'e');
        $qb->leftJoin('s.lieu', 'l');
        $qb->leftJoin('s.organisateur', 'o');

        $qb->andWhere('s.dateHeureDebut > :dateSubstract1Month');
        $qb->setParameter('dateSubstract1Month', $dateSubstract1Month);

        $qb->orderBy('s.id', 'ASC');
        $qb->setMaxResults(100);
        $query = $qb->getQuery();
        //$qb->getResult();
        return $query->getArrayResult();
    }

    /**
     * @return Sorties[] Returns an array of Sorties objects
     */
    public function findByFilter(Array $array)
    {
        $dateSubstract1Month = new \DateTime();
        $dateSubstract1Month->modify('-1 months');

        $dateNow = new \DateTime();

        $qb = $this->createQueryBuilder('s');
        $qb->select(array('s', 'i', 'e', 'l', 'o', 'p'));
        $qb->leftJoin('s.inscriptions', 'i');
        $qb->leftJoin('i.participant', 'p');
        $qb->leftJoin('s.etat', 'e');
        $qb->leftJoin('s.lieu', 'l');
        $qb->leftJoin('s.organisateur', 'o');

        $qb->andWhere('s.dateHeureDebut > :dateSubstract1Month');
        $qb->setParameter('dateSubstract1Month', $dateSubstract1Month);

        if($array["campus"] != null)
        {
            $qb->andWhere('s.siteOrganisateur = :campus');
            $qb->setParameter('campus', $array["campus"]);
        }
        if($array["nomSortie"] != null)
        {
            $qb->andWhere('s.nom LIKE :nomSortie');
            $qb->setParameter('nomSortie', '%'.$array["nomSortie"].'%');
        }
        if($array["dateDebut"] != null && $array["dateFin"] != null)
        {
            $qb->andWhere('s.dateHeureDebut BETWEEN :dateDebut and :dateFin');
            $qb->setParameter('dateDebut', $array["dateDebut"]);
            $qb->setParameter('dateFin', $array["dateFin"]);
        }
        if($array["cbOrganisateur"] == true)
        {
            $qb->andWhere('s.organisateur = :user');
            $qb->setParameter('user', $array["user"]);
        }
        if($array["cbInscrit"] == true)
        {
            $qb->andWhere('i.participant = :user');
            $qb->setParameter('user', $array["user"]);
        }
        if($array["cbNonInscrit"] == true)
        {
            $qb->andWhere('i.participant <> :user');
            $qb->setParameter('user', $array["user"]);
        }
        if($array["cbSortiePassee"] == true)
        {
            $qb->andWhere('s.etat = :etat');
            $qb->setParameter('etat', 5);
        }
        $qb->orderBy('s.id', 'ASC');
        $qb->setMaxResults(100);
        $query = $qb->getQuery();
        //$qb->getResult();
        return $query->getArrayResult();
    }


    /*
    public function findOneBySomeField($value): ?Sorties
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
