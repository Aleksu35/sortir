<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

// Utiliser DateTimeType pour la gestion des dates
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie',
            ])
            ->add('dateHeureDebut', DateTimeType::class, [  // Utilisez DateTimeType ici
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text',  // Affichage sous forme d'un seul champ de texte
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [  // Utilisez DateTimeType ici aussi
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',  // Affichage sous forme d'un seul champ de texte
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label' => 'Nombre de places',
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée',
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos : ',
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus',
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => '--Choisis ton campus--',
            ])
            ->add('ville', EntityType::class, [
                'label' => 'Ville',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped' => false
            ])
            ->add('lieu', EntityType::class, [
            'label' => 'Lieu',
            'class' => Lieu::class,
            'choice_label' => 'nom',
            'placeholder' => '--Choisis ton lieu--',
            'mapped' => true,
            'required' => true
            ])

            ->add('etat', EntityType::class, [
        'label' => 'Etat de la sortie',
        'class' => Etat::class
    ]);
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
