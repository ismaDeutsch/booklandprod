<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Entity\GenreSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    /**
     * @param GenreSearch $search
     * @return array
     */
    public function findGenre(GenreSearch $search): array{
        $query = $this->createQueryBuilder('g');
        if($search->getGenre()->count() > 0){
            $k = 0;
            foreach ($search->getGenre() as $genre){
                $k++;
                $query = $query->orWhere("g = :genre$k")
                    ->setParameter(":genre$k", $genre);
            }
        }
        return $query->getQuery()->getResult();
    }

    public function findAVG(Genre $genre){
        $query = $this->createQueryBuilder('g')
            ->select('AVG (l.nbpages) AS avg')
            ->innerJoin('g.livres', 'l')
            ->where('g.id = :id')
            ->setParameter('id', $genre->getId());
        dump($query->getQuery()->getResult());
        return $query->getQuery()->getResult();
    }

    public function findSUM(Genre $genre){
        $query = $this->createQueryBuilder('g')
            ->select('SUM (l.nbpages) AS sum')
            ->innerJoin('g.livres', 'l')
            ->where('g.id = :id')
            ->setParameter('id', $genre->getId());

        return $query->getQuery()->getResult();
    }

    /*public function findAuteurGenre(){
        $query = $this->createQueryBuilder('g')
            ->select('g.id, g.nom, a.id, a.nom_prenom')
            ->innerJoin('g.livres', 'l')
            ->innerJoin('l.auteurs', 'a')
            ->where('g.id = 1');
        dump($query->getQuery()->getResult());
        //return $res;
    }*/

    public function findGAuteurs(Genre $genre){
        $query = $this->createQueryBuilder('g')
            ->select('DISTINCT a.nom_prenom')
            ->innerJoin('g.livres', 'l')
            ->innerJoin('l.auteurs', 'a')
            ->where('g.id = :id')
            ->setParameter('id', $genre->getId());

        return $query->getQuery()->getResult();
    }

    public function findGSAuteurs(){
        $array = $this->findAll();
        $result = array();
        foreach ($array as $genre){
            array_push($result, $this->findGAuteurs($genre));
        }
        return $result;
    }


    // /**
    //  * @return Genre[] Returns an array of Genre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Genre
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
