<?php


namespace App\Controller;

use App\Entity\AuteurSearch;
use App\Form\AuteurSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    public function index(Request $request)
    {
        $search = new AuteurSearch();
        $form = $this->createForm(AuteurSearchType::class, $search);
        $form->handleRequest($request);
        $auteurs = $this->repo->findAuthor($search);
        return $this->render('Auteur/auteur.html.twig', [
            'auteur' => $auteurs,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/auteur/show/{id}", name="auteur.show", requirements={"id"="\d+"})
     */
    public function show(Auteur $auteur, Request $request){
        if(!$auteur)
            return $this->redirectToRoute('auteur.index');
       // $books = $this->repo->findBooksWrite($auteur);
        $genres = $this->repo->findGenre($auteur);

        $form = $this->createFormBuilder()
            ->add('note', IntegerType::class, [
                'label' => false
            ])
            ->add('increase', SubmitType::class, [
                'label' => '+',
                'attr' => ['class' => 'increase']
            ])
            ->add('degrease', SubmitType::class, [
                'label' => '-',
                'attr' => ['class' => 'degrease']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->getClickedButton() === $form->get('increase')){
            foreach ($auteur->getLivres() as $book){
                if(($book->getNote() - $form->getData()['note']) <= 20){
                    $this->repo->increaseNote($book, $form->getData());
                    $this->addFlash('success', "La note du 
                    livre ".$book->getTitre()." a bien était augmenter");
                }else{
                    $this->addFlash('error', "La note du 
                    livre ".$book->getTitre()." ne peut pas étre superieru à 20");
                }
            }
            return $this->redirectToRoute('auteur.show', [
                'id' => $auteur->getId()
            ]);
        }
        if($form->getClickedButton() === $form->get('degrease')){
            foreach ($auteur->getLivres() as $book){
                if(($book->getNote() - $form->getData()['note']) >= 0){
                    $this->repo->degreaseNote($book, $form->getData());
                    $this->addFlash('success', "La note du 
                    livre ".$book->getTitre()." a bien était deminuer");
                }else{
                    $this->addFlash('error', "La note du 
                    livre ".$book->getTitre()." ne peut pas étre en doussous de 0");
                }
            }
            return $this->redirectToRoute('auteur.show', [
                'id' => $auteur->getId()
            ]);
        }

        return $this->render('Auteur/show.html.twig', [
            'auteur' => $auteur,
            'genres' => $genres,
            'form' => $form->createView()
            ]);
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
        return $this->render('Auteur/edit.html.twig', [
            'form' => $form->createView(),
            'auteur' => $auteur
        ]);
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