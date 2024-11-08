<?php

// src/Form/FiltreSortieType.php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\FiltreSortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom', // Change 'nom' par le champ que tu veux afficher dans la liste déroulante
                'placeholder' => 'Sélectionnez un campus',
                'required' => false,
            ])
            ->add('rechercheParNomDeSortie', TextType::class, [
                'required' => false,
                'label' => 'Nom de la sortie'
            ])
            ->add('dateRechercheDebut', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Date de début'
            ])
            ->add('dateRechercheFin', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Date de fin'
            ])
            ->add('sortiesDeLorganisateur', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties de l\'organisateur'
            ])
            ->add('sortiesDontJeSuisInscrit', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties dont je suis inscrit'
            ])
            ->add('sortiesDontJeNeSuisPasInscrit', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties dont je ne suis pas inscrit'
            ])
            ->add('sortiesPassees', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties passées'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FiltreSortie::class,
            'method' => 'GET',
            'csrf_protection' => false, // Désactive la protection CSRF si ce n'est pas nécessaire
        ]);
    }
}

