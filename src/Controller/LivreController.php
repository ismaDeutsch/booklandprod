<?php


namespace App\Controller;

use App\Entity\Livre;
use App\Entity\LivreSearch;
use App\Form\LivreSearchType;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LivreController extends AbstractController
{

    /**
     * @var LivreRepository
     */
    private $repo;

    public function __construct(LivreRepository $repo)
    {
        $this->repo = $repo;
    }


    public function index(Request $request)
    {
        $search = new LivreSearch();
        $form = $this->createForm(LivreSearchType::class, $search);
        $form->handleRequest($request);
        $livre = $this->repo->findBook($search);
        dump($this->repo->findDistinctSexe());
        return $this->render('Livre/livre.html.twig', [
            'livre' => $livre,
            'form' => $form->createView()
        ]);
    }


    public function show($id, Request $request)
    {
        $livre = $this->repo->find($id);
        if (!$livre) {
            return $this->redirectToRoute("livre.index");
        }
        $form = $this->createFormBuilder()
            ->add('note', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'min' => 1,
                    'max' => 20
                ]
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
            if(($form->getData()['note'] + $livre->getNote())  <= 20){
                $this->repo->increaseNote($livre, $form->getData());
                $this->addFlash('success', "La note du livre a bien était augmenté");
            }else{
                $this->addFlash('error', "La note du livre ne peut pas depasser 20");
            }
            return $this->redirectToRoute('livre.show', [
                'id' => $livre->getId()
            ]);
        }
        if($form->getClickedButton() === $form->get('degrease')){
            if(($livre->getNote() - $form->getData()['note']) >= 0){
                $this->repo->degreaseNote($livre, $form->getData());
                $this->addFlash('success', "La note du livre a bien était deminuer");
            }else{
                $this->addFlash('error', "La note du livre ne peut pas depasser 0");
            }
            return $this->redirectToRoute('livre.show', [
                'id' => $livre->getId()
            ]);
        }

        return $this->render('Livre/show.html.twig', [
            'livre' => $livre,
            'form' => $form->createView()
        ]);
    }


    public function edit($id, Request $request)
    {
        $livre = $this->repo->find($id);
        if (!$livre) {
            return $this->redirectToRoute("genre.index");
        }
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', "Le livre a bien était modifier");
            return $this->redirectToRoute('livre.index');
        }
        return $this->render('Livre/edit.html.twig', ['form' => $form->createView(), 'livre' => $livre]);
    }


    public function add(Request $request)
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($livre);
            $em->flush();
            $this->addFlash('success', 'Le livre a bien était créer');
            return $this->redirectToRoute('livre.index');
        }
        return $this->render('Livre/add.html.twig', ['form' => $form->createView()]);
    }


    public function delete(Livre $livre, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $livre->getId(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($livre);
            $em->flush();
            $this->addFlash('success', 'Le livre a bien était supprimé');
        }
        return $this->redirectToRoute('livre.index');
    }


    public function nationality(){
        $res = $this->repo->findDistinctNationality();
        return $this->render('Livre/nationality.html.twig', [
            'livre' => $res
        ]);
    }


    public function parite(){
        $res = $this->repo->findDistinctSexe();
        return $this->render('Livre/parite.html.twig', [
            'livre' => $res
        ]);
    }
}