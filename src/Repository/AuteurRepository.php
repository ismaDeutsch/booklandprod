<?php

namespace App\Repository;

use App\Entity\Auteur;
use App\Entity\AuteurSearch;
use App\Entity\Livre;
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
        if($search->getSexe()){
            $query = $query->andWhere('a.sexe = :sexe')
                ->setParameter('sexe', $search->getSexe());
        }
        if($search->getNationality()){
            $query = $query->andWhere('a.nationalite = :nationality')
                ->setParameter('nationality', $search->getNationality());
        }
        if($search->getYear()){
            $query =$query->andWhere('a.date_de_naissance = :date')
                ->setParameter('date', $search->getYear()
                    ->format('Y-m-d'));
        }

        return $query->getQuery()->getResult();
    }

    public function increaseNote(Livre $livre, $n){
        $query = $this->getEntityManager()
            ->createQuery("UPDATE App\Entity\Livre l
                            SET l.note = l.note + :note
                            WHERE l.id = :id")
            ->setParameter('note', $n)
            ->setParameter('id', $livre->getId());
        return $query->execute();
    }

    public function degreaseNote(Livre $livre, $n){
        $query = $this->getEntityManager()
            ->createQuery("UPDATE App\Entity\Livre l
                            SET l.note = l.note - :note
                            WHERE l.id = :id")
        ->setParameter('note', $n)
        ->setParameter('id', $livre->getId());
        return $query->execute();
    }


    public function findGenre(Auteur $auteur){
        $query = $this->createQueryBuilder('a')
            ->select('g.id', 'g.nom')
            ->distinct('g.id, g.nom')
            ->join('a.livres', 'l')
            ->join('l.genres', 'g')
            ->where('a.id = :auteur')
            ->setParameter('auteur', $auteur->getId());

        return $query->getQuery()->getResult();
    }

    /*public function findBooksWrite(Auteur $auteur){
        $query = $this->createQueryBuilder('a')
            ->;
        return $query->getQuery()->getResult();
    }*/

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
