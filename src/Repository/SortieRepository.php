<?php

namespace App\Repository;

use App\Entity\FiltreSortie;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findWithFilters(FiltreSortie $filtre)
    {
        $qb = $this->createQueryBuilder('s');

        if ($filtre->getCampus()) {
            $qb->andWhere('s.campus = :campus')
                ->setParameter('campus', $filtre->getCampus());

        }

        if ($filtre->getRechercheParNomdeSortie()) {
            $qb->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', '%' . $filtre->getRechercheParNomdeSortie() . '%');
        }

        if ($filtre->getDateRechercheDebut()) {
            $qb->andWhere('s.dateHeureDebut >= :debut')
                ->setParameter('debut', $filtre->getDateRechercheDebut());
        }

        if ($filtre->getDateRechercheFin()) {
            $qb->andWhere('s.dateHeureDebut <= :fin')
                ->setParameter('fin', $filtre->getDateRechercheFin());
        }

        return $qb->getQuery()->getResult();
    }


}



