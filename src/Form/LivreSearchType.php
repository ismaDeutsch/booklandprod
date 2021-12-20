<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\LivreSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Titre'
                ]
            ])
            ->add('publication_date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => false,
            ])
            ->add('publication_date_end', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => false,
            ])
            ->add('numberOfPage', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Pages'
                ]
            ])
            ->add('authors', EntityType::class, [
                'required' => false,
                'label' => false,
                'choice_label' => 'nomPrenom',
                'class' => Auteur::class,
                'multiple' => true,
                'attr' => ['class' => 'author']
            ])
            ->add('genres', EntityType::class, [
                'required' => false,
                'label' => false,
                'choice_label' => 'nom',
                'class' => Genre::class,
                'multiple' => true,
                'attr' => ['class' => 'genre']
            ])
            ->add('noteMin', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Note min'
                ]
            ])
            ->add('noteMax', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Note max'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LivreSearch::class,
            'method' =>'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
