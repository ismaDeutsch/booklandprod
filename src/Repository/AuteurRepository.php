<?php

namespace App\Repository;

use App\Entity\Auteur;
use App\Entity\AuteurSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Auteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auteur[]    findAll()
 * @method Auteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auteur::class);
    }

    /**
     * @param AuteurSearch $search
     * @return array
     */
    public function findAuthor(AuteurSearch $search): array{
        $query = $this->createQueryBuilder('a');
        if($search->getMinLivre()){
            $query = $query->innerJoin('a.livres', 'l')
                ->groupBy('a.id')
                ->andHaving($query->expr()->gte($query->expr()->count('l.id'),
                    $search->getMinLivre()));
        }
        if($search->getName()){
            $query = $query->Where('a.nom_prenom LIKE :name')
                ->setParameter('name', '%'.$search->getName().'%');
        }
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Auteur[] Returns an array of Auteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Auteur
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
