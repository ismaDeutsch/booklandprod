<?php


namespace App\Controller;


use App\Entity\Genre;
use App\Entity\GenreSearch;
use App\Form\GenreSearchType;
use App\Form\GenreType;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GenreController extends AbstractController
{

    /**
     * @var GenreRepository
     */
    private $repo;
    public function __construct(GenreRepository $repo)
    {
        $this->repo = $repo;
    }


    public function index(Request $request){
        $search = new GenreSearch();
        $form = $this->createForm(GenreSearchType::class, $search);
        $form->handleRequest($request);
        $genre = $this->repo->findGenre($search);
        return $this->render('Genre/genre.html.twig', [
            "genre" => $genre,
            "auteurs" => $this->repo->findGSAuteurs(),
            'form' => $form->createView()
        ]);
    }


    public function add(Request $request){
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre);
            $em->flush();
            $this->addFlash('success', "Le genre a bien était créer");
            return $this->redirectToRoute('genre.index');
        }
        return $this->render('Genre/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    public function edit($id, Request $request){
        $genre = $this->repo->find($id);
        if (!$genre) {
            return $this->redirectToRoute("genre.index");
        }

        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', "Le genre a bien était modifier");
            return $this->redirectToRoute('genre.index');
        }
        return $this->render('Genre/edit.html.twig', [
            'form' => $form->createView(),
            'Genre' => $genre
        ]);
    }


    public function show($id){
        $genre = $this->repo->find($id);
        if (!$genre) {
            return $this->redirectToRoute("genre.index");
        }
        $avg = $this->repo->findAVG($genre);
        $sum = $this->repo->findSUM($genre);
        return $this->render('Genre/show.html.twig', [
            'genre' => $genre,
            'avg' => $avg,
            'sum' => $sum
        ]);
    }


    public function delete(Genre $genre, Request $request){
        if($genre->getLivres()->count() == 0){
            if ($this->isCsrfTokenValid('delete' . $genre->getId(), $request->get('_token'))) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($genre);
                $em->flush();
                $this->addFlash('success', 'Le genre a bien était supprimé');
            }
        }else{
            $this->addFlash('error', 'Le genre posséde des livres');
        }
        return $this->redirectToRoute('genre.index');
    }
}