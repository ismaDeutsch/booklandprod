<?php

namespace App\Repository;

use App\Entity\Livre;
use App\Entity\LivreSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    /**
     * @param LivreSearch $search
     * @return array
     */
    public function findBook(LivreSearch $search): array {
        $query = $this->createQueryBuilder('l');
        if($search->getAuthors()->count() > 0){
            $k = 0;
            foreach ($search->getAuthors() as $author){
                $k++;
                $query = $query->andWhere(":auteur$k MEMBER OF l.auteurs")
                    ->setParameter("auteur$k", $author);
            }
        }
        if($search->getTitle()){
            $query = $query->andWhere('l.titre LIKE :title')
                ->setParameter('title', $search->getTitle().'%');
        }
        if($search->getPublicationDate()){
            $query = $query->andWhere('l.date_de_parution >= :dateBegin')
                ->setParameter('dateBegin', $search->getPublicationDate()->format('Y-m-d'));
        }
        if($search->getPublicationDateEnd()){
            $query = $query->andWhere('l.date_de_parution <= :dateEnd')
                ->setParameter('dateEnd', $search->getPublicationDateEnd()->format('Y-m-d'));
        }
        if($search->getNumberOfPage()){
            $query = $query->andWhere('l.nbpages = :page')
                ->setParameter('page', $search->getNumberOfPage());
        }
        if($search->getNoteMin()){
            $query = $query->andWhere('l.note >= :noteMin')
                ->setParameter('noteMin', $search->getNoteMin());
        }
        if($search->getNoteMax()){
            $query = $query->andWhere('l.note <= :noteMax')
                ->setParameter('noteMax', $search->getNoteMax());
        }
        if($search->getGenres()->count() > 0){
            $k = 0;
            foreach ($search->getGenres() as $genre){
                $k++;
                $query = $query->andWhere(":genre$k MEMBER OF l.genres")
                    ->setParameter("genre$k", $genre);
            }
        }
        return $query->getQuery()->getResult();
    }

    public function findDistinctAuteur()
    {
       $array = $this->find(1);
        $res = array();
        $res1 = array();


        foreach ($array->getAuteurs() as $auteur){
            $flag = true;
            foreach ($array->getAuteurs() as $auteur1){
                if($auteur1->getNationalite() !== $auteur->getNationalite()){
                    $flag = false;
                    break;
                }
            }
            if($flag)
                array_push($res, $auteur);
        }

        if(count($res) == $array->getAuteurs()->count())
            array_push($res1, $array);


        /*array_push($res, $array->getAuteurs()->get(0));

        foreach ($array->getAuteurs() as $auteur) {
           // dump($auteur);
            foreach ($res as $val){
                if($auteur->getNationalite() === $val->getNationalite()){
                    $flag = true;
                    break;
                }
            }
            if(!$flag)
                array_push($res, $auteur);
            $flag = false;

        }

         ($array->getAuteurs() as $item){
            foreach ($res as $elm){
                if($item->getNationalite() === $elm->getNationalite){
                    $flag = true;
                }
                break;
            }
            if(!$flag){
                array_push($res, $item);
            }
         if (!in_array($auteur, $res)) {
                array_push($res, $auteur);
            }
        }*/
        //dump($res1);
    }

    public function parite(){
        $array = $this->find(1);
        $m = array();
        $f = array();
        $r = array();
        foreach ($array->getAuteurs() as $auteur){
            if($auteur->getSexe() == 'M')
                array_push($m, $auteur);
            else{
                array_push($f, $auteur);
            }
        }
        if(count($m) == count($f))
            array_push($r,'yes');

        dump($r);
    }



    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
