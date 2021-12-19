<?php


namespace App\Controller;


use App\Entity\Genre;
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

    /**
     * @Route ("/genre", name="genre.index")
     */
    public function index(){
        $genre = $this->repo->findAll();
        return $this->render('Genre/genre.html.twig', [
            "genre" => $genre
        ]);
    }

    /**
     * @Route ("/genre/add", name="genre.add")
     */
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

    /**
     * @Route ("/genre/edit/{id}", name="genre.edit", requirements={"id" = "\d+"}, methods="GET|POST")
     */
    public function edit(Genre $genre, Request $request){
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

    /**
     * @Route("/genre/show/{id}", name="genre.show", requirements={"id" = "\d+"})
     */
    public function show($id){
        $genre = $this->repo->find($id);
        if (!$genre) {
            return $this->redirectToRoute("genre.index");
        }
        return $this->render('Genre/show.html.twig', ['Genre' => $genre]);
    }

    /**
     * @Route ("/genre/edit/{id}", name="genre.delete", requirements={"id" = "\d+"}, methods="DELETE")
     */
    public function delete(Genre $genre, Request $request){
        if ($this->isCsrfTokenValid('delete' . $genre->getId(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($genre);
            $em->flush();
            $this->addFlash('success', 'Le genre a bien était supprimé');
        }
        return $this->redirectToRoute('genre.index');
    }
}