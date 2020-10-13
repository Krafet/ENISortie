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
    public function findByFilter(Campus $campus, string $nomSortie, Date $dateDebut, Date $dateFin, bool $cbOrginisateur,bool $cbInscrit,bool $cbNonInscrit,bool $cbSortiePassee)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.siteOrganisateur = :campus')
            ->andWhere('s.nom LIKE "%:nomSortie%"')
            ->andWhere('s.dateHeureDebut LIKE "%:nomSortie%"')
            ->andWhere('s.nom LIKE "%:nomSortie%"')
            ->andWhere('s.nom LIKE "%:nomSortie%"')
            ->andWhere('s.nom LIKE "%:nomSortie%"')
            ->andWhere('s.nom LIKE "%:nomSortie%"')
            ->join('s.inscriptions', 'i')
            ->setParameter('campus', $campus->getId())
            ->setParameter('nomSortie', $nomSortie)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
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
