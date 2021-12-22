<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class HomeController extends AbstractController {

    /**
     * @Route("/home",  name="home")
     */
    public function home(Request $request) {
        $form = $this->createFormBuilder()
            ->add('search', TextType::class, [
                'label' => false,
                'attr' => [
                    'style' => 'width: 400px'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);

        return $this->render('home.html.twig', [
            'form' => $form->createView()
        ]);
    }
}