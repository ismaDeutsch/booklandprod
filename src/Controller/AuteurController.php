<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuteurRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Auteur;
use App\Form\AuteurType;

class AuteurController extends AbstractController
{
    /**
     * @var AuteurRepository
     */
    private $repo;

    public function __construct(AuteurRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/auteur", name="auteur.index")
     */

    public function index()
    {
        $auteurs = $this->repo->findAll();
        return $this->render('Auteur/auteur.html.twig', [
            'auteur' => $auteurs
        ]);
    }

    /**
     * @Route("/auteur/show/{id}", name="auteur.show", requirements={"id"="\d+"})
     */
    public function show($id){
        $auteur = $this->repo->find($id);
        if(!$auteur)
            return $this->redirectToRoute('auteur.index');
        return $this->render('Auteur/show.html.twig', ['auteur' => $auteur]);
    }

    /**
     * @Route("/auteur/edit/{id}", name="auteur.edit", requirements={"id"="\d+"}, methods="GET|POST")
     */
    public function edit(Auteur $auteur, Request $request)
    {
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'L\'auteur a bien était modifier');
            return $this->redirectToRoute('auteur.index');
        }
        return $this->render('Auteur/edit.html.twig', ['form' => $form->createView(), 'auteur' => $auteur]);
    }

    /**
     * @Route("/auteur/add", name="auteur.add")
     */
    public function add(Request $request)
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($auteur);
            $em->flush();
            $this->addFlash('success', 'L\'auteur a bien était créer');
            return $this->redirectToRoute('auteur.index');
        }

        return $this->render('Auteur/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/auteur/edit/{id}", name="auteur.delete", methods="DELETE")
     */
    public function delete(Auteur $auteur, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $auteur->getId(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($auteur);
            $em->flush();
            $this->addFlash('success', 'L\'auteur a bien était supprimé');
        }
        return $this->redirectToRoute('auteur.index');
    }
}