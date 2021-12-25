<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    public function home() {
        return $this->render('home.html.twig');
    }

    public function init(){
        $genreNom = ["science fiction", "policier", "philosophie",
            "économie", "psychologie"];
        $auteurNomPrenom = ["Richard Thaler", "Cass Sunstein", "Francis Gabrelot",
            "Ayn Rand", "Duschmol", "Nancy Grave",
            "James Enckling", "Jean Dupont"];
        $auteurSexe = ["M","M","M","F","M","F","M","M"];
        $auteurDateDeNaissance = ["12/12/1945","23/11/1943","29/01/1967",
            "21/06/1950","23/12/2001","24/10/1952",
            "03/07/1970","03/07/1970"];
        $auteurNationalite = ["USA","allemagne","france","russie",
            "groland","USA","USA","france"];
        $livreTitre = ["Symfonystique","La grève","Symfonyland","Négociation Complexe",
            "Ma vie","Ma vie : suite","Le monde comme volonté et comme représentation"];
        $livreIsbn = ["978-2-07-036822-8"," 978-2-251-44417-8","978-2-212-55652-0",
            "978-2-0807-1057-4","978-0-300-12223-7","978-0-141-18776-1","978-0-141-18786-0"];
        $livreNbpages = [117,1245,131,234,5,5,1987];
        $livreDateDeParution = ["20/01/2008","12/06/1961","17/09/1980","25/09/1992",
            "08/11/2021","09/11/2021","09/11/1821"];
        $livreNote = [8,19,15,16,3,1,19];



        foreach ($genreNom as $nom){
            $genre = new Genre;
            $genre->setNom($nom);
            $this->getDoctrine()->getManager()->persist($genre);
            $this->getDoctrine()->getManager()->flush();
        }
        for ($i=0;$i<count($auteurNomPrenom);$i++){
            $auteur = new Auteur;
            $auteur->setNomPrenom($auteurNomPrenom[$i]);
            $auteur->setSexe($auteurSexe[$i]);
            $auteur->setDateDeNaissance(\DateTime::createFromFormat('d/m/Y',$auteurDateDeNaissance[$i]));
            $auteur->setNationalite($auteurNationalite[$i]);
            $this->getDoctrine()->getManager()->persist($auteur);
            $this->getDoctrine()->getManager()->flush();
        }
        $livreGenre = [[$this->getDoctrine()->getManager()->getRepository(Genre::class)->findOneBy([ 'nom' => "policier"]), $this->getDoctrine()->getManager()->getRepository(Genre::class)->findOneBy([ 'nom' => "philosophie"])],
            [$this->getDoctrine()->getManager()->getRepository(Genre::class)->findOneBy([ 'nom' => "philosophie"])],[$this->getDoctrine()->getManager()->getRepository(Genre::class)->findOneBy([ 'nom' => "science fiction"])],
            [$this->getDoctrine()->getManager()->getRepository(Genre::class)->findOneBy([ 'nom' => "psychologie"])],[$this->getDoctrine()->getManager()->getRepository(Genre::class)->findOneBy([ 'nom' => "policier"])],
            [$this->getDoctrine()->getManager()->getRepository(Genre::class)->findOneBy([ 'nom' => "policier"])],[$this->getDoctrine()->getManager()->getRepository(Genre::class)->findOneBy([ 'nom' => "philosophie"])]];

        $livreAuteur = [[$this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Francis Gabrelot"]), $this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Ayn Rand"]),
            $this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Nancy Grave"])],[$this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Ayn Rand"]),
            $this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "James Enckling"])], [$this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Jean Dupont"]),
            $this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "James Enckling"]), $this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Ayn Rand"])],
            [$this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Richard Thaler"]), $this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Cass Sunstein"])],
            [$this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Jean Dupont"])],[$this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Jean Dupont"])],
            [$this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Nancy Grave"]), $this->getDoctrine()->getManager()->getRepository(Auteur::class)->findOneBy(['nom_prenom' => "Francis Gabrelot"])]];

        for ($i=0;$i<count($livreTitre);$i++){
            $livre = new Livre;
            $livre->setTitre($livreTitre[$i]);
            $livre->setIsbn($livreIsbn[$i]);
            $livre->setNbpages($livreNbpages[$i]);
            $livre->setDateDeParution(\DateTime::createFromFormat('d/m/Y',$livreDateDeParution[$i]));
            $livre->setNote($livreNote[$i]);
            foreach ($livreGenre[$i] as $genre){
                $livre->addGenre($genre);
            }
            foreach($livreAuteur[$i] as $auteur){
                $livre->addAuteur($auteur);
            }
            $this->getDoctrine()->getManager()->persist($livre);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute("home");
    }
}